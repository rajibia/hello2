<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanyBillingRequest;
use App\Http\Requests\UpdateCompanyBillingRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Bill;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\IpdBill;
use App\Models\Maternity;
use App\Models\MedicineBill;
use App\Models\Patient;
use App\Models\PathologyTest;
use App\Models\RadiologyTest;
use App\Models\Insurance;
use Illuminate\Http\Request;
use Flash;
use Response;
use PDF;

class CompanyBillingController extends AppBaseController
{
    /**
     * Display a listing of the Company Billing.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $patients = Patient::with('patientUser')->get()->where('patientUser.status', '=', 1)->pluck('patientUser.full_name', 'id')->sort();
        $companies = Company::pluck('name', 'id');
        // Define status options that match the balance-based logic in the view
        $status = [
            0 => 'Unpaid',
            1 => 'Paid',
            2 => 'Partially Paid'
        ];

        // Get all bill types for company patients
        $bills = collect();

        // Define available bill types
        $billTypes = [
            'opd_invoice' => 'OPD Invoice',
            'medicine_bill' => 'Medicine Bill',
            'ipd_bill' => 'IPD Bill',
            'laboratory_test' => 'Laboratory Test',
            'radiology_test' => 'Radiology Test',
            'maternity_bill' => 'Maternity Bill'
        ];

        // OPD Invoices
        $opdInvoices = collect();
        if (!$request->filled('type') || $request->type == 'opd_invoice') {
            $opdInvoices = Invoice::with(['patient.patientUser', 'patient.company'])
            ->whereHas('patient', function ($query) {
                $query->whereNotNull('company_id');
            })
            ->when($request->filled('patient_id'), function ($query) use ($request) {
                $query->where('patient_id', $request->patient_id);
            })
            ->when($request->filled('company_id'), function ($query) use ($request) {
                $query->whereHas('patient', function ($q) use ($request) {
                    $q->where('company_id', $request->company_id);
                });
            })

            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('invoice_date', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('invoice_date', '<=', $request->date_to);
            })
            ->get()
            ->map(function ($invoice) {
                return (object) [
                    'id' => $invoice->id,
                    'patient' => $invoice->patient,
                    'invoice_date' => $invoice->invoice_date,
                    'amount' => $invoice->amount,
                    'balance' => $invoice->balance,
                    'status' => $invoice->status,
                    'bill_type' => 'OPD Invoice',
                    'created_at' => $invoice->created_at,
                    'model_type' => 'Invoice'
                ];
            });
        }

        // Medicine Bills
        $medicineBills = collect();
        if (!$request->filled('type') || $request->type == 'medicine_bill') {
            $medicineBills = \App\Models\MedicineBill::with(['patient.patientUser', 'patient.company'])
            ->whereHas('patient', function ($query) {
                $query->whereNotNull('company_id');
            })
            ->when($request->filled('patient_id'), function ($query) use ($request) {
                $query->where('patient_id', $request->patient_id);
            })
            ->when($request->filled('company_id'), function ($query) use ($request) {
                $query->whereHas('patient', function ($q) use ($request) {
                    $q->where('company_id', $request->company_id);
                });
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('bill_date', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('bill_date', '<=', $request->date_to);
            })
            ->get()
            ->map(function ($bill) {
                return (object) [
                    'id' => $bill->id,
                    'patient' => $bill->patient,
                    'invoice_date' => $bill->bill_date ?? $bill->created_at,
                    'amount' => $bill->total ?? $bill->net_amount,
                    'balance' => ($bill->total ?? $bill->net_amount) - ($bill->paid_amount ?? 0),
                    'status' => $bill->payment_status,
                    'bill_type' => 'Medicine Bill',
                    'created_at' => $bill->created_at,
                    'model_type' => 'MedicineBill'
                ];
            });
        }

        // IPD Bills
        $ipdBills = collect();
        if (!$request->filled('type') || $request->type == 'ipd_bill') {
            $ipdBills = \App\Models\IpdBill::with(['ipdPatient.patient.patientUser', 'ipdPatient.patient.company'])
            ->whereHas('ipdPatient.patient', function ($query) {
                $query->whereNotNull('company_id');
            })
            ->when($request->filled('patient_id'), function ($query) use ($request) {
                $query->whereHas('ipdPatient', function ($q) use ($request) {
                    $q->where('patient_id', $request->patient_id);
                });
            })
            ->when($request->filled('company_id'), function ($query) use ($request) {
                $query->whereHas('ipdPatient.patient', function ($q) use ($request) {
                    $q->where('company_id', $request->company_id);
                });
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })
            ->get()
            ->map(function ($bill) {
                return (object) [
                    'id' => $bill->id,
                    'patient' => $bill->ipdPatient->patient,
                    'invoice_date' => $bill->created_at,
                    'amount' => $bill->net_payable_amount,
                    'balance' => $bill->net_payable_amount - $bill->total_payments,
                    'status' => ($bill->net_payable_amount - $bill->total_payments) <= 0 ? 1 : 0,
                    'bill_type' => 'IPD Bill',
                    'created_at' => $bill->created_at,
                    'model_type' => 'IpdBill'
                ];
            });
        }

        // Pathology Tests (Laboratory)
        $pathologyTests = collect();
        if (!$request->filled('type') || $request->type == 'laboratory_test') {
            $pathologyTests = \App\Models\PathologyTest::with(['patient.patientUser', 'patient.company'])
            ->whereHas('patient', function ($query) {
                $query->whereNotNull('company_id');
            })
            ->when($request->filled('patient_id'), function ($query) use ($request) {
                $query->where('patient_id', $request->patient_id);
            })
            ->when($request->filled('company_id'), function ($query) use ($request) {
                $query->whereHas('patient', function ($q) use ($request) {
                    $q->where('company_id', $request->company_id);
                });
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })
            ->get()
            ->map(function ($test) {
                return (object) [
                    'id' => $test->id,
                    'patient' => $test->patient,
                    'invoice_date' => $test->created_at,
                    'amount' => $test->total,
                    'balance' => $test->balance,
                    'status' => $test->balance <= 0 ? 1 : 0,
                    'bill_type' => 'Laboratory Test',
                    'created_at' => $test->created_at,
                    'model_type' => 'PathologyTest'
                ];
            });
        }

        // Radiology Tests
        $radiologyTests = collect();
        if (!$request->filled('type') || $request->type == 'radiology_test') {
            $radiologyTests = \App\Models\RadiologyTest::with(['patient.patientUser', 'patient.company'])
            ->whereHas('patient', function ($query) {
                $query->whereNotNull('company_id');
            })
            ->when($request->filled('patient_id'), function ($query) use ($request) {
                $query->where('patient_id', $request->patient_id);
            })
            ->when($request->filled('company_id'), function ($query) use ($request) {
                $query->whereHas('patient', function ($q) use ($request) {
                    $q->where('company_id', $request->company_id);
                });
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })
            ->get()
            ->map(function ($test) {
                return (object) [
                    'id' => $test->id,
                    'patient' => $test->patient,
                    'invoice_date' => $test->created_at,
                    'amount' => $test->total,
                    'balance' => $test->balance,
                    'status' => $test->balance <= 0 ? 1 : 0,
                    'bill_type' => 'Radiology Test',
                    'created_at' => $test->created_at,
                    'model_type' => 'RadiologyTest'
                ];
            });
        }

        // Maternity Bills
        $maternityBills = collect();
        if (!$request->filled('type') || $request->type == 'maternity_bill') {
            $maternityBills = \App\Models\Maternity::with(['patient.patientUser', 'patient.company'])
            ->whereHas('patient', function ($query) {
                $query->whereNotNull('company_id');
            })
            ->when($request->filled('patient_id'), function ($query) use ($request) {
                $query->where('patient_id', $request->patient_id);
            })
            ->when($request->filled('company_id'), function ($query) use ($request) {
                $query->whereHas('patient', function ($q) use ($request) {
                    $q->where('company_id', $request->company_id);
                });
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('appointment_date', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('appointment_date', '<=', $request->date_to);
            })
            ->get()
            ->map(function ($maternity) {
                return (object) [
                    'id' => $maternity->id,
                    'patient' => $maternity->patient,
                    'invoice_date' => $maternity->appointment_date,
                    'amount' => $maternity->standard_charge,
                    'balance' => $maternity->standard_charge - ($maternity->paid_amount ?? 0),
                    'status' => ($maternity->paid_amount ?? 0) >= $maternity->standard_charge ? 1 : 0,
                    'bill_type' => 'Maternity Bill',
                    'created_at' => $maternity->created_at,
                    'model_type' => 'Maternity'
                ];
            });
        }

        // Combine all bills
        $allBills = $opdInvoices->concat($medicineBills)
                                ->concat($ipdBills)
                                ->concat($pathologyTests)
                                ->concat($radiologyTests)
                                ->concat($maternityBills);

        // Apply status filter after combining all bills
        if ($request->filled('status')) {
            $allBills = $allBills->filter(function ($bill) use ($request) {
                $statusFilter = $request->status;

                // Map filter values to actual status logic
                if ($statusFilter == 1) { // Paid
                    return $bill->balance == 0;
                } elseif ($statusFilter == 0) { // Unpaid
                    return $bill->balance == $bill->amount;
                } elseif ($statusFilter == 2) { // Partially Paid
                    return $bill->balance > 0 && $bill->balance < $bill->amount;
                }

                return true; // Show all if no valid filter
            });
        }

        $allBills = $allBills->sortByDesc('created_at');

        // Paginate the combined results
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $allBills->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $bills = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $allBills->count(),
            $perPage,
            $currentPage,
            [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'pageName' => 'page'
            ]
        );

        $filters = [
            'patient_id' => $request->get('patient_id'),
            'company_id' => $request->get('company_id'),
            'status' => $request->get('status'),
            'type' => $request->get('type'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to')
        ];

        return view('company_billing.index', compact('bills', 'patients', 'companies', 'status', 'filters', 'billTypes'));
    }

    /**
     * Show the specified Company Bill.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        // First try to find as Invoice
        $bill = Invoice::with([
            'patient.user',
            'patient.company',
            'invoiceItems.charge.chargeCategory'
        ])->find($id);

        if ($bill) {
            return view('company_billing.show')->with('invoice', $bill);
        }

        // Try to find as MedicineBill
        $bill = \App\Models\MedicineBill::with([
            'patient.user',
            'patient.company',
            'saleMedicine.medicine'
        ])->find($id);

        if ($bill) {
            return view('company_billing.show_medicine')->with('bill', $bill);
        }

        // Try to find as IpdBill
        $bill = \App\Models\IpdBill::with([
            'ipdPatient.patient.user',
            'ipdPatient.patient.company',
            'ipdBillItems.charge.chargeCategory'
        ])->find($id);

        if ($bill) {
            return view('company_billing.show_ipd')->with('bill', $bill);
        }

        // Try to find as PathologyTest
        $bill = \App\Models\PathologyTest::with([
            'patient.user',
            'patient.company',
            'pathologyTestItems.pathologytesttemplate'
        ])->find($id);

        if ($bill) {
            return view('company_billing.show_pathology')->with('bill', $bill);
        }

        // Try to find as RadiologyTest
        $bill = \App\Models\RadiologyTest::with([
            'patient.user',
            'patient.company',
            'radiologyTestItems.radiologytesttemplate'
        ])->find($id);

        if ($bill) {
            return view('company_billing.show_radiology')->with('bill', $bill);
        }

        // Try to find as Maternity
        $bill = \App\Models\Maternity::with([
            'patient.user',
            'patient.company'
        ])->find($id);

        if ($bill) {
            return view('company_billing.show_maternity')->with('bill', $bill);
        }

        Flash::error('Company Bill not found');
        return redirect(route('company-billing.index'));
    }

    /**
     * Convert Company Invoice to PDF
     *
     * @param int $id
     *
     * @return Response
     */
    public function convertToPdf($id)
    {
        $invoice = Invoice::with([
            'patient.user',
            'patient.company',
            'invoiceItems.charge.chargeCategory'
        ])->find($id);

        if (empty($invoice)) {
            Flash::error('Company Invoice not found');
            return redirect(route('company-billing.index'));
        }

        $data = [
            'invoice' => $invoice,
            'currencySymbol' => getCurrencySymbol()
        ];

        $pdf = PDF::loadView('company_billing.invoice_pdf', $data);
        return $pdf->stream('company-invoice-' . $invoice->invoice_id . '.pdf');
    }
}
