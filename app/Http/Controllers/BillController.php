<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillRequest;
use App\Http\Requests\UpdateBillRequest;
use App\Models\Bill;
use App\Models\Invoice;
use App\Models\IpdBill;
use App\Models\IpdPatientDepartment;
use App\Models\MedicineBill;
use App\Models\Patient;
use App\Models\PathologyTest;
use App\Models\RadiologyTest;
use App\Models\MaternityPatientDepartment;
use App\Repositories\BillRepository;
use \PDF;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Models\Setting;

class BillController extends AppBaseController
{
    /** @var BillRepository */
    private $billRepository;

    public function __construct(BillRepository $billRepo)
    {
        $this->billRepository = $billRepo;
    }

    public function index()
    {
        $setting = Setting::pluck('key','value');
       // dd($setting);

        return view('bills.index',compact('setting'));
    }

    public function create()
    {
        $data = $this->billRepository->getSyncList(false);
//dd($data);
        return view('bills.create')->with($data);
    }

    public function store(CreateBillRequest $request)
    {
        try {
            DB::beginTransaction();

            $input = $request->all();
            $patientId = Patient::with('patientUser')->whereId($input['patient_id'])->first();
            $birthDate = $patientId->patientUser->dob;
            $billDate = Carbon::parse($input['bill_date'])->toDateString();

            if (! empty($birthDate) && $billDate < $birthDate) {
                return $this->sendError(__('messages.bed_assign.assign_date_should_not_be_smaller_than_patient_birth_date'));
            }

            $bill = $this->billRepository->saveBill($request->all());
            $this->billRepository->saveNotification($input);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($bill, __('messages.bill.bill').' '.__('messages.common.saved_successfully'));
    }

    public function show(Bill $bill)
    {
        $bill = Bill::with(['billItems.medicine', 'patient', 'patientAdmission'])->find($bill->id);

        if ($bill->patientAdmission) {
            $admissionDate = Carbon::parse($bill->patientAdmission->admission_date);
            $dischargeDate = Carbon::parse($bill->patientAdmission->discharge_date);
            $bill->totalDays = $admissionDate->diffInDays($dischargeDate) + 1;
        }

        return view('bills.show')->with('bill', $bill);
    }

    public function showPatientBills($id){
        // Eager load related bill collections to ensure counts and lists are available
        $patient = Patient::with([
            'patientUser',
            'medicine_bills',
            'ipdPatientDepartments',
            'invoices',
            'pathologyTests',
            'radiologyTests',
            'maternity',
            'address'
        ])->find($id);

        // Check if patient is a company patient (has company_id)
        if ($patient && $patient->company_id) {
            // Redirect or show error for company patients
            return redirect()->back()->with('error', 'Cannot view bills for company patients. Please use the company billing section.');
        }

        return view('bills.view_bill',compact('patient'));
    }
    public function paySelectedBills(Request $request, $patientId)
    {
        try {
            $medicineBillInput = $request->input('medicineBills', []);
            $ipdBillInput = $request->input('ipdBills', []);
            $opdBillInput = $request->input('opdBills', []);
            $pathologyBillInput = $request->input('pathologyBills', []);
            $radiologyBillInput = $request->input('radiologyBills', []);
            $maternityBillInput = $request->input('maternityBills', []);

            // Normalize inputs: accept either array of IDs or array of {id, amount} objects
            $normalize = function ($input) {
                $items = [];
                foreach ((array)$input as $it) {
                    if (is_array($it)) {
                        $id = $it['id'] ?? null;
                        $amount = isset($it['amount']) ? floatval($it['amount']) : null;
                    } elseif (is_object($it)) {
                        $id = $it->id ?? null;
                        $amount = isset($it->amount) ? floatval($it->amount) : null;
                    } else {
                        $id = $it;
                        $amount = null;
                    }

                    if ($id !== null) {
                        $items[] = ['id' => $id, 'amount' => $amount];
                    }
                }
                return $items;
            };

            $medicineBillItems = $normalize($medicineBillInput);
            $ipdBillItems = $normalize($ipdBillInput);
            $opdBillItems = $normalize($opdBillInput);
            $pathologyBillItems = $normalize($pathologyBillInput);
            $radiologyBillItems = $normalize($radiologyBillInput);
            $maternityBillItems = $normalize($maternityBillInput);

            // Extract plain ID arrays for whereIn queries
            $medicineBillIds = array_map(fn($i) => $i['id'], $medicineBillItems);
            $ipdBillIds = array_map(fn($i) => $i['id'], $ipdBillItems);
            $opdBillIds = array_map(fn($i) => $i['id'], $opdBillItems);
            $pathologyBillIds = array_map(fn($i) => $i['id'], $pathologyBillItems);
            $radiologyBillIds = array_map(fn($i) => $i['id'], $radiologyBillItems);
            $maternityBillIds = array_map(fn($i) => $i['id'], $maternityBillItems);

            \Log::info('Payment request received', [
                'patient_id' => $patientId,
                'medicine_bills' => $medicineBillIds,
                'ipd_bills' => $ipdBillIds,
                'opd_bills' => $opdBillIds,
                'pathology_bills' => $pathologyBillIds,
                'radiology_bills' => $radiologyBillIds,
                'maternity_bills' => $maternityBillIds
            ]);

            if (empty($medicineBillIds) && empty($ipdBillIds) && empty($opdBillIds) &&
                empty($pathologyBillIds) && empty($radiologyBillIds) && empty($maternityBillIds)) {
                return response()->json([
                    'error' => __('messages.bill.no_bills_selected')
                ], 422);
            }

        // Pay and update medicine bills (support partial payments)
        if (!empty($medicineBillIds)) {
            $medicineBills = MedicineBill::whereIn('id', $medicineBillIds)
                ->where('payment_status', 0)
                ->get();

            $medicineMap = [];
            foreach ($medicineBillItems as $it) {
                $medicineMap[$it['id']] = $it['amount'];
            }

            foreach ($medicineBills as $bill) {
                $requestedAmount = isset($medicineMap[$bill->id]) ? floatval($medicineMap[$bill->id]) : null;
                $currentPaid = floatval($bill->paid_amount ?? 0);
                $remaining = floatval($bill->total ?? 0) - $currentPaid;

                if ($requestedAmount === null) {
                    // full payment
                    $bill->paid_amount = $bill->total;
                    $bill->payment_status = 1;
                } else {
                    $toAdd = min($requestedAmount, $remaining);
                    $bill->paid_amount = $currentPaid + $toAdd;
                    if (floatval($bill->paid_amount) >= floatval($bill->total)) {
                        $bill->payment_status = 1;
                    }
                }
                $bill->save();
            }
        }

        // Pay and update IPD bills (support partial via associated IpdBill.total_payments)
        if (!empty($ipdBillIds)) {
            $ipdBills = IpdPatientDepartment::whereIn('id', $ipdBillIds)
                ->where('bill_status', 0)
                ->get();

            $ipdMap = [];
            foreach ($ipdBillItems as $it) {
                $ipdMap[$it['id']] = $it['amount'];
            }

            foreach ($ipdBills as $ipdPatient) {
                $requestedAmount = $ipdMap[$ipdPatient->id] ?? null;

                if ($ipdPatient->bill) {
                    $billRec = $ipdPatient->bill;
                    $currentPayments = floatval($billRec->total_payments ?? 0);
                    $totalCharges = floatval($billRec->total_charges ?? 0);
                    if ($requestedAmount === null) {
                        $billRec->total_payments = $totalCharges;
                        $ipdPatient->bill_status = 1;
                    } else {
                        $toAdd = min($requestedAmount, max(0, $totalCharges - $currentPayments));
                        $billRec->total_payments = $currentPayments + $toAdd;
                        if ($billRec->total_payments >= $totalCharges) {
                            $ipdPatient->bill_status = 1;
                        }
                    }
                    $billRec->save();
                } else {
                    // No related bill record: fallback to marking as paid only if full payment requested
                    if ($requestedAmount === null) {
                        $ipdPatient->bill_status = 1;
                    }
                }

                $ipdPatient->save();
            }
        }

        // Pay and update OPD invoices (support partial payments)
        if (!empty($opdBillIds)) {
            $opdBills = Invoice::whereIn('id', $opdBillIds)
                ->where('status', 1)
                ->get();

            $opdMap = [];
            foreach ($opdBillItems as $it) {
                $opdMap[$it['id']] = $it['amount'];
            }

            foreach ($opdBills as $invoice) {
                $requestedAmount = $opdMap[$invoice->id] ?? null;
                $invoiceAmount = floatval($invoice->amount ?? 0);
                $currentPaid = floatval($invoice->paid_amount ?? 0);

                if ($requestedAmount === null) {
                    // full payment
                    $invoice->status = 0;
                    $invoice->balance = 0;
                    $invoice->total = $invoice->amount;
                    $invoice->paid_amount = $invoice->amount;
                } else {
                    $toAdd = min($requestedAmount, max(0, $invoiceAmount - $currentPaid));
                    $invoice->paid_amount = $currentPaid + $toAdd;
                    $invoice->balance = max(0, $invoiceAmount - $invoice->paid_amount);
                    if ($invoice->balance <= 0) {
                        $invoice->status = 0;
                    }
                }
                $invoice->save();
            }
        }

        // Pay and update Pathology Tests (support partial)
        if (!empty($pathologyBillIds)) {
            $pathologyTests = PathologyTest::whereIn('id', $pathologyBillIds)
                ->where('balance', '>', 0)
                ->get();

            $pathMap = [];
            foreach ($pathologyBillItems as $it) {
                $pathMap[$it['id']] = $it['amount'];
            }

            foreach ($pathologyTests as $test) {
                $requestedAmount = $pathMap[$test->id] ?? null;
                $currentBalance = floatval($test->balance ?? 0);
                if ($requestedAmount === null) {
                    $test->amount_paid = $currentBalance;
                    $test->balance = 0;
                } else {
                    $toAdd = min($requestedAmount, $currentBalance);
                    $test->amount_paid = floatval($test->amount_paid ?? 0) + $toAdd;
                    $test->balance = max(0, $currentBalance - $toAdd);
                }
                $test->save();
            }
        }

        // Pay and update Radiology Tests (support partial)
        if (!empty($radiologyBillIds)) {
            $radiologyTests = RadiologyTest::whereIn('id', $radiologyBillIds)
                ->where('balance', '>', 0)
                ->get();

            $radMap = [];
            foreach ($radiologyBillItems as $it) {
                $radMap[$it['id']] = $it['amount'];
            }

            foreach ($radiologyTests as $test) {
                $requestedAmount = $radMap[$test->id] ?? null;
                $currentBalance = floatval($test->balance ?? 0);
                if ($requestedAmount === null) {
                    $test->amount_paid = $currentBalance;
                    $test->balance = 0;
                } else {
                    $toAdd = min($requestedAmount, $currentBalance);
                    $test->amount_paid = floatval($test->amount_paid ?? 0) + $toAdd;
                    $test->balance = max(0, $currentBalance - $toAdd);
                }
                $test->save();
            }
        }

        // Pay and update Maternity Bills (support partial)
        if (!empty($maternityBillIds)) {
            $maternityBills = MaternityPatientDepartment::whereIn('id', $maternityBillIds)
                ->where('paid_amount', '<', DB::raw('standard_charge'))
                ->get();

            $matMap = [];
            foreach ($maternityBillItems as $it) {
                $matMap[$it['id']] = $it['amount'];
            }

            foreach ($maternityBills as $maternity) {
                $requestedAmount = $matMap[$maternity->id] ?? null;
                $currentPaid = floatval($maternity->paid_amount ?? 0);
                $standard = floatval($maternity->standard_charge ?? 0);
                if ($requestedAmount === null) {
                    $maternity->paid_amount = $standard;
                } else {
                    $toAdd = min($requestedAmount, max(0, $standard - $currentPaid));
                    $maternity->paid_amount = $currentPaid + $toAdd;
                }
                $maternity->save();
            }
        }

            \Log::info('Payment completed successfully', [
                'patient_id' => $patientId,
                'processed_bills' => [
                    'medicine' => count($medicineBillIds),
                    'ipd' => count($ipdBillIds),
                    'opd' => count($opdBillIds),
                    'pathology' => count($pathologyBillIds),
                    'radiology' => count($radiologyBillIds),
                    'maternity' => count($maternityBillIds)
                ]
            ]);

            return response()->json([
                'message' => __('messages.bill.selected_bills_paid_successfully')
            ]);
        } catch (\Exception $e) {
            \Log::error('Payment processing error', [
                'patient_id' => $patientId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Payment processing failed: ' . $e->getMessage()
            ], 500);
        }
    }
    public function edit(Bill $bill)
    {
        $bill->billItems;
        $isEdit = true;
        $data = $this->billRepository->getSyncList($isEdit);
        $data['bill'] = $bill;

        return view('bills.edit')->with($data);
    }

    public function update(Bill $bill, UpdateBillRequest $request)
    {
        $input = $request->all();
        $patientId = Patient::with('patientUser')->whereId($input['patient_id'])->first();
        $birthDate = $patientId->patientUser->dob;
        $billDate = Carbon::parse($input['bill_date'])->toDateString();

        if (! empty($birthDate) && $billDate < $birthDate) {
            return $this->sendError(__('messages.bed_assign.assign_date_should_not_be_smaller_than_patient_birth_date'));
        }

        $bill = $this->billRepository->updateBill($bill->id, $request->all());

        return $this->sendResponse($bill, __('messages.bill.bill').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(Bill $bill)
    {
        $this->billRepository->delete($bill->id);

        return $this->sendSuccess(__('messages.bill.bill').' '.__('messages.common.deleted_successfully'));
    }

    public function getPatientAdmissionDetails(Request $request)
    {
        $inputs = $request->all();
        $patientAdmissionDetails = $this->billRepository->patientAdmissionDetails($inputs);

        return $this->sendResponse($patientAdmissionDetails, 'Details retrieved successfully.');
    }

    public function convertToPdf(Bill $bill)
    {
        $bill->billItems;
        $data = $this->billRepository->getSyncListForCreate($bill->id);
        $data['bill'] = $bill;
        // $pdf = PDF::loadView('bills.bill_pdf', $data);

        // return $pdf->stream('bill.pdf');
        return view('bills.bill_pdf')->with($data);
    }
}
