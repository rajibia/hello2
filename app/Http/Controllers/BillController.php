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
        $patient = Patient::find($id);

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
            $medicineBillIds = $request->input('medicineBills', []);
            $ipdBillIds = $request->input('ipdBills', []);
            $opdBillIds = $request->input('opdBills', []);
            $pathologyBillIds = $request->input('pathologyBills', []);
            $radiologyBillIds = $request->input('radiologyBills', []);
            $maternityBillIds = $request->input('maternityBills', []);

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

        // Pay and update medicine bills
        if (!empty($medicineBillIds)) {
            $medicineBills = MedicineBill::whereIn('id', $medicineBillIds)
                ->where('payment_status', 0)
                ->get();

            foreach ($medicineBills as $bill) {
                $bill->payment_status = 1;
                $bill->paid_amount = $bill->total;
                $bill->save();
            }
        }

        // Pay and update IPD bills
        if (!empty($ipdBillIds)) {
            $ipdBills = IpdPatientDepartment::whereIn('id', $ipdBillIds)
                ->where('bill_status', 0)
                ->get();

            foreach ($ipdBills as $ipdPatient) {
                $ipdPatient->bill_status = 1;
                $ipdPatient->save();

                // Also update the associated IpdBill if it exists
                if ($ipdPatient->bill) {
                    $ipdPatient->bill->total_payments = $ipdPatient->bill->total_charges;
                    $ipdPatient->bill->save();
                }
            }
        }

        // Pay and update OPD invoices
        if (!empty($opdBillIds)) {
            $opdBills = Invoice::whereIn('id', $opdBillIds)
                ->where('status', 1)
                ->get();

            foreach ($opdBills as $invoice) {
                $invoice->status = 0;
                $invoice->balance = 0;
                $invoice->total = $invoice->amount;
                $invoice->paid_amount = $invoice->amount;
                $invoice->save();
            }
        }

        // Pay and update Pathology Tests
        if (!empty($pathologyBillIds)) {
            $pathologyTests = PathologyTest::whereIn('id', $pathologyBillIds)
                ->where('balance', '>', 0)
                ->get();

            foreach ($pathologyTests as $test) {
                $test->amount_paid = $test->balance;
                $test->balance = 0;
                $test->save();
            }
        }

        // Pay and update Radiology Tests
        if (!empty($radiologyBillIds)) {
            $radiologyTests = RadiologyTest::whereIn('id', $radiologyBillIds)
                ->where('balance', '>', 0)
                ->get();

            foreach ($radiologyTests as $test) {
                $test->amount_paid = $test->balance;
                $test->balance = 0;
                $test->save();
            }
        }

        // Pay and update Maternity Bills
        if (!empty($maternityBillIds)) {
            $maternityBills = MaternityPatientDepartment::whereIn('id', $maternityBillIds)
                ->where('paid_amount', '<', DB::raw('standard_charge'))
                ->get();

            foreach ($maternityBills as $maternity) {
                $maternity->paid_amount = $maternity->standard_charge;
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
