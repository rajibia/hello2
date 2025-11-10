<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Accountant;
use App\Models\Charge;
use App\Models\ChargeType;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Receptionist;
use App\Models\Setting;
use App\Models\User;
use Arr;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Validator;

/**
 * Class InvoiceRepository
 *
 * @version February 24, 2020, 5:51 am UTC
 */
class InvoiceRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'patient_id',
        'invoice_date',
        'amount',
        'status',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Invoice::class;
    }


    public function getSyncList()
    {
        $invoiceRepo = app(BillRepository::class);
        $data['charges'] = Charge::with('chargeCategory')->get()->pluck('chargeCategory.name', 'id')->sort();
        $data['chargeTypes'] = ChargeType::where('status', 1)->get()->pluck('name', 'id')->toArray();
        asort($data['chargeTypes']);
        $data['allCharges'] = Charge::with('chargeCategory')->get()->toArray();
        $data['associateCharges'] = $this->getAssociateChargesList($data['charges']);
        $data['patients'] = $invoiceRepo->getPatientList();
        $data['accounts'] = Account::pluck('name', 'id')->toArray();
        $invoiceStatusArr = Invoice::STATUS_ARR;
        unset($invoiceStatusArr[Invoice::STATUS_ALL]);
        $data['statusArr'] = $invoiceStatusArr;

        return $data;
    }

    public function getAssociateChargesList($result)
    {
        $charges = [];
        foreach ($result as $key => $item) {
            $charges[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $charges;
    }

    public function saveInvoice($input)
    {
        $invoiceItemInputArray = Arr::only($input, ['charge_id', 'description', 'quantity', 'price']);
        $invoiceExist = Invoice::where('invoice_id', $input['invoice_id'])->exists();

        if ($invoiceExist) {
            throw new UnprocessableEntityHttpException('Invoice id already exist');

            return false;
        }

        $input['paid_amount'] = isset($input['paid_amount']) ? (float)$input['paid_amount'] : 0;
        $input['change'] = isset($input['change']) ? (float)$input['change'] : 0;
        $input['status'] = $input['status'] ?? 1;
        
        $invoice = $this->create(Arr::only($input, ['patient_id', 'invoice_date', 'discount', 'status', 'invoice_id', 'currency_symbol', 'paid_amount', 'change']));
        $totalAmount = 0;
        $invoiceItemInput = $this->prepareInputForInvoiceItem($invoiceItemInputArray);

        foreach ($invoiceItemInput as $key => $data) {
            $validator = Validator::make($data, InvoiceItem::$rules);

            if ($validator->fails()) {
                throw new UnprocessableEntityHttpException($validator->errors()->first());
            }

            $data['total'] = $data['price'] * $data['quantity'];
            $totalAmount += $data['total'];

            $invoiceItem = new InvoiceItem($data);
            $invoice->invoiceItems()->save($invoiceItem);
        }
        $invoice->amount = $totalAmount;
        
        // Calculate balance: total amount - discount - paid amount
        $discountAmount = isset($input['discount']) ? (float)$input['discount'] : 0;
        $invoice->balance = $totalAmount - $discountAmount - $input['paid_amount'];
        
        $invoice->save();

        return $invoice;
    }

    public function saveInvoicePatient($input) {
       
        $charge = Charge::where('id', $input['charge_id'])->first();
        if($charge)
        {
            // Generate invoice_id if not provided
            if (!isset($input['invoice_id']) || empty($input['invoice_id'])) {
                $input['invoice_id'] = Invoice::generateUniqueInvoiceId();
            }
            
            // Set default values for payment fields
            $input['paid_amount'] = isset($input['paid_amount']) ? (float)$input['paid_amount'] : 0;
            $input['change'] = isset($input['change']) ? (float)$input['change'] : 0;
            $input['status'] = $input['status'] ?? 1;
            
            // Calculate amounts
            $totalAmount = $charge->standard_charge ?? 0;
            $discountAmount = isset($input['discount']) ? (float)$input['discount'] : 0;
            
            // Set amount and calculate balance
            $input['amount'] = $totalAmount;
            $input['balance'] = $totalAmount - $discountAmount - $input['paid_amount'];
            
            $invoice = $this->create(Arr::only($input, ['patient_id', 'invoice_date', 'discount', 'status', 'invoice_id', 'currency_symbol', 'paid_amount', 'change', 'amount', 'balance']));
    
            $invoiceItem = new InvoiceItem();
            $invoiceItem->invoice_id = $invoice->id;
            $invoiceItem->charge_id = $input['charge_id'];
            $invoiceItem->description = $input['description'];
            $invoiceItem->quantity = 1;
            $invoiceItem->price = $charge->standard_charge ?? 0;
            $invoiceItem->total = $charge->standard_charge ?? 0;
            // $invoiceItem->created_at = Carbon::now();
            // $invoiceItem->updated_at = Carbon::now();
            $invoiceItem->currency_symbol = getCurrencySymbol();
            $invoiceItem->save();
        }
        return $invoice ?? null;
    }

    public function prepareInputForInvoiceItem($input)
    {
        $items = [];
        foreach ($input as $key => $data) {
            foreach ($data as $index => $value) {
                $items[$index][$key] = $value;
                if (! (isset($items[$index]['price']) && $key == 'price')) {
                    continue;
                }
                $items[$index]['price'] = removeCommaFromNumbers($items[$index]['price']);
            }
        }

        return $items;
    }

    public function updateInvoice($invoiceId, $input)
    {
        $invoiceItemInputArr = Arr::only($input, ['charge_id', 'description', 'quantity', 'price', 'id']);

        $invoice = $this->update(Arr::only($input, ['patient_id', 'invoice_date', 'discount', 'status', 'currency_symbol']), $invoiceId);
        $totalAmount = 0;

        $invoiceItemInput = $this->prepareInputForInvoiceItem($invoiceItemInputArr);
        foreach ($invoiceItemInput as $key => $data) {
            $validator = Validator::make($data, InvoiceItem::$rules, [
                'charge_id.integer' => 'Please select a charge',
            ]);

            if ($validator->fails()) {
                throw new UnprocessableEntityHttpException($validator->errors()->first());
            }

            $data['total'] = $data['price'] * $data['quantity'];
            $invoiceItemInput[$key] = $data;
            $totalAmount += $data['total'];
        }

        $invoiceItemRepo = app(InvoiceItemRepository::class);
        $invoiceItemRepo->updateInvoiceItem($invoiceItemInput, $invoice->id);

        $invoice->amount = $totalAmount;
        $invoice->save();

        return $invoice;
    }

    public function getSyncListForCreate($invoiceId = null)
    {
        $data['setting'] = Setting::all()->pluck('value', 'key')->toArray();

        return $data;
    }

    public function saveNotification($input)
    {
        $input['status'] = $input['status'] ?? 1;
        $patient = Patient::with('patientUser')->where('id', $input['patient_id'])->first();
        $receptionists = Receptionist::pluck('user_id', 'id')->toArray();
        $accountants = Accountant::pluck('user_id', 'id')->toArray();
        $status = $input['status'] == 0 ? Invoice::STATUS_ARR[Invoice::PENDING] : Invoice::STATUS_ARR[Invoice::PAID];
        $userIds = [
            $patient->user_id => Notification::NOTIFICATION_FOR[Notification::PATIENT],
        ];

        foreach ($receptionists as $key => $userId) {
            $userIds[$userId] = Notification::NOTIFICATION_FOR[Notification::RECEPTIONIST];
        }

        foreach ($accountants as $key => $userId) {
            $userIds[$userId] = Notification::NOTIFICATION_FOR[Notification::ACCOUNTANT];
        }

        $adminUser = User::role('Admin')->first();
        $allUsers = $userIds + [$adminUser->id => Notification::NOTIFICATION_FOR[Notification::ADMIN]];
        $users = getAllNotificationUser($allUsers);

        foreach ($users as $key => $notification) {
            if ($notification == Notification::NOTIFICATION_FOR[Notification::PATIENT]) {
                $title = $patient->patientUser->full_name.' your invoice has been '.$status;
            } else {
                $title = $patient->patientUser->full_name.' invoice has been '.$status;
            }

            addNotification([
                Notification::NOTIFICATION_TYPE['Invoice'],
                $key,
                $notification,
                $title,
            ]);
        }
    }
}
