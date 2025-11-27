<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\IpdBill;
use App\Models\Maternity;
use App\Models\MedicineBill;
use App\Models\Patient;
use App\Models\PathologyTest;
use App\Models\RadiologyTest;
use App\Models\Payment;
use App\Models\Setting;
use App\Repositories\InvoiceRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use \PDF;
use DB;
use Exception;

class TodayPaymentReportsController extends AppBaseController
{
    /** @var InvoiceRepository */
    private $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->invoiceRepository = $invoiceRepo;
    }

    public function index(Request $request)
    {
        $statusArr = Invoice::STATUS_ARR;
        
        // Define revenue types
        $revenueTypes = [
            'opd_invoice' => 'OPD Invoice',
            'medicine_bill' => 'Medicine Bill',
            'ipd_bill' => 'IPD Bill',
            'laboratory_test' => 'Laboratory Test',
            'radiology_test' => 'Radiology Test',
            'maternity_bill' => 'Maternity Bill',
        ];
        
        // Get filters from request
        $type = $request->get('type');
        $fromDate = $request->get('from_date', Carbon::today()->subDays(30)->format('Y-m-d')); // Default to last 30 days
        $toDate = $request->get('to_date', Carbon::today()->format('Y-m-d'));
        
        $filters = [
            'type' => $type,
            'from_date' => $fromDate,
            'to_date' => $toDate,
        ];
        
        $revenues = collect();
        
        // Collect OPD Invoices (paid invoices)
        if (!$type || $type === 'opd_invoice') {
            $opdRevenues = Invoice::where('status', 1) // paid status
                ->whereBetween('invoice_date', [$fromDate, $toDate])
                ->with(['patient.patientUser', 'patient.company'])
                ->get()
                ->map(function($invoice) {
                    return [
                        'type' => 'OPD Invoice',
                        'reference_id' => $invoice->invoice_id,
                        'patient_name' => $invoice->patient->patientUser->full_name ?? 'N/A',
                        'company_name' => $invoice->patient->company->name ?? 'N/A',
                        'amount' => $invoice->amount,
                        'date' => $invoice->invoice_date,
                        'description' => 'OPD Invoice Payment',
                        'status' => 1, // Paid
                    ];
                });
            $revenues = $revenues->merge($opdRevenues);
        }
        
        // Collect Medicine Bills
        if (!$type || $type === 'medicine_bill') {
            $medicineRevenues = MedicineBill::whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
                ->where('paid_amount', '>', 0)
                ->with(['patient.patientUser', 'patient.company'])
                ->get()
                ->map(function($bill) {
                    return [
                        'type' => 'Medicine Bill',
                        'reference_id' => $bill->bill_number,
                        'patient_name' => $bill->patient->patientUser->full_name ?? 'N/A',
                        'company_name' => $bill->patient->company->name ?? 'N/A',
                        'amount' => $bill->paid_amount,
                        'date' => $bill->created_at->format('Y-m-d'),
                        'description' => 'Medicine Bill Payment',
                        'status' => 1, // Paid
                    ];
                });
            $revenues = $revenues->merge($medicineRevenues);
        }
        
        // Collect IPD Bills
        if (!$type || $type === 'ipd_bill') {
            $ipdRevenues = IpdBill::whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
                ->where('total_payments', '>', 0)
                ->with(['ipdPatient.patient.patientUser', 'ipdPatient.patient.company'])
                ->get()
                ->map(function($bill) {
                    return [
                        'type' => 'IPD Bill',
                        'reference_id' => 'IPD-' . $bill->id,
                        'patient_name' => $bill->ipdPatient->patient->patientUser->full_name ?? 'N/A',
                        'company_name' => $bill->ipdPatient->patient->company->name ?? 'N/A',
                        'amount' => $bill->total_payments,
                        'date' => $bill->created_at->format('Y-m-d'),
                        'description' => 'IPD Bill Payment',
                        'status' => 1, // Paid
                    ];
                });
            $revenues = $revenues->merge($ipdRevenues);
        }
        
        // Collect Laboratory Tests
        if (!$type || $type === 'laboratory_test') {
            $labRevenues = PathologyTest::whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
                ->where('amount_paid', '>', 0)
                ->with(['patient.patientUser', 'patient.company'])
                ->get()
                ->map(function($test) {
                    return [
                        'type' => 'Laboratory Test',
                        'reference_id' => 'LAB-' . $test->id,
                        'patient_name' => $test->patient->patientUser->full_name ?? 'N/A',
                        'company_name' => $test->patient->company->name ?? 'N/A',
                        'amount' => $test->amount_paid,
                        'date' => $test->created_at->format('Y-m-d'),
                        'description' => 'Laboratory Test Payment',
                        'status' => 1, // Paid
                    ];
                });
            $revenues = $revenues->merge($labRevenues);
        }
        
        // Collect Radiology Tests
        if (!$type || $type === 'radiology_test') {
            $radioRevenues = RadiologyTest::whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
                ->where('amount_paid', '>', 0)
                ->with(['patient.patientUser', 'patient.company'])
                ->get()
                ->map(function($test) {
                    return [
                        'type' => 'Radiology Test',
                        'reference_id' => 'RAD-' . $test->id,
                        'patient_name' => $test->patient->patientUser->full_name ?? 'N/A',
                        'company_name' => $test->patient->company->name ?? 'N/A',
                        'amount' => $test->amount_paid,
                        'date' => $test->created_at->format('Y-m-d'),
                        'description' => 'Radiology Test Payment',
                        'status' => 1, // Paid
                    ];
                });
            $revenues = $revenues->merge($radioRevenues);
        }
        
        // Collect Maternity Bills
        if (!$type || $type === 'maternity_bill') {
            $maternityRevenues = Maternity::whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
                ->where('paid_amount', '>', 0)
                ->with(['patient.patientUser', 'patient.company'])
                ->get()
                ->map(function($bill) {
                    return [
                        'type' => 'Maternity Bill',
                        'reference_id' => 'MAT-' . $bill->id,
                        'patient_name' => $bill->patient->patientUser->full_name ?? 'N/A',
                        'company_name' => $bill->patient->company->name ?? 'N/A',
                        'amount' => $bill->paid_amount,
                        'date' => $bill->created_at->format('Y-m-d'),
                        'description' => 'Maternity Bill Payment',
                        'status' => 1, // Paid
                    ];
                });
            $revenues = $revenues->merge($maternityRevenues);
        }
        
        // Sort by date (newest first) and paginate
        $revenues = $revenues->sortByDesc('date');
        
        // Store the actual count before pagination
        $actualTotalCount = $revenues->count();
        $totalRevenue = $revenues->sum('amount');
        
        $perPage = 50; // Increase to 50 to show more records per page
        $currentPage = request()->get('page', 1);
        $revenues = new \Illuminate\Pagination\LengthAwarePaginator(
            $revenues->forPage($currentPage, $perPage),
            $actualTotalCount, // Use the actual count here
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('today_payment_reports.index', compact('statusArr', 'revenues', 'revenueTypes', 'filters', 'actualTotalCount', 'totalRevenue'));
    }

    public function create(Request $request)
    {
        $data = $this->invoiceRepository->getSyncList();
        $data['patient_id'] = $request->query()['ref_p_id'] ?? '';

        return view('invoices.create')->with($data);
    }

    public function store(CreateInvoiceRequest $request)
    {
        try {
            DB::beginTransaction();
            $bill = $this->invoiceRepository->saveInvoice($request->all());
            $this->invoiceRepository->saveNotification($request->all());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($bill, __('messages.invoice.invoice').' '.__('messages.common.saved_successfully'));
    }

    public function show(Invoice $invoice)
    {
        $data['hospitalAddress'] = Setting::where('key', '=', 'hospital_address')->first()->value;
        $data['invoice'] = Invoice::with(['invoiceItems.charge', 'patient.address'])->find($invoice->id);

        return view('invoices.show')->with($data);
    }

    public function edit(Invoice $invoice)
    {
        $invoice->invoiceItems;
        $data = $this->invoiceRepository->getSyncList();
        $data['invoice'] = $invoice;

        return view('invoices.edit')->with($data);
    }

    public function update(Invoice $invoice, UpdateInvoiceRequest $request)
    {
        try {
            DB::beginTransaction();
            $bill = $this->invoiceRepository->updateInvoice($invoice->id, $request->all());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($bill, __('messages.invoice.invoice').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(Invoice $invoice)
    {
        $this->invoiceRepository->delete($invoice->id);

        return $this->sendSuccess(__('messages.invoice.invoice').' '.__('messages.common.deleted_successfully'));
    }

    public function convertToPdf(Invoice $invoice)
    {
        $invoice->invoiceItems;
        $data = $this->invoiceRepository->getSyncListForCreate($invoice->id);
        $data['invoice'] = $invoice;
        $data['currencySymbol'] = getCurrencySymbol();
        
        // $pdf = PDF::loadView('invoices.invoice_pdf', $data);

        // return $pdf->stream('invoice.pdf');
        return view('invoices.invoice_pdf')->with($data);
    }
}
