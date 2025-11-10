<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Department;
use App\Models\Notification;
use App\Models\Supplier;
use App\Models\Receptionist;
use App\Models\User;
use Exception;
use App\Models\PatientAdmission;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class SupplierRepository
 *
 * @version February 14, 2020, 5:53 am UTC
 */
class SupplierRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'email',
        'phone',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Supplier::class;
    }

    public function store($input, $mail = true)
    {
        $settings = App::make(SettingRepository::class)->getSyncList();
        try {
            $input['status'] = isset($input['status']) ? 1 : 0;
            $input['name'] = (! empty($input['name'])) ? $input['name'] : null;
            $input['email'] = (! empty($input['email'])) ? $input['email'] : null;
            $input['phone'] = preparePhoneNumber($input, 'phone');
            $input['address'] = (! empty($input['address'])) ? $input['address'] : null;
            $input['city'] = (! empty($input['city'])) ? $input['city'] : null;
            $supplier = Supplier::create($input);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }

    public function update($input, $supplier)
    {
        try {
            $user = Supplier::find($supplier->id);
            $input['status'] = isset($input['status']) ? 1 : 0;
            $input['name'] = (! empty($input['name'])) ? $input['name'] : null;
            $input['email'] = (! empty($input['email'])) ? $input['email'] : null;
            $input['phone'] = preparePhoneNumber($input, 'phone');
            $input['address'] = (! empty($input['address'])) ? $input['address'] : null;
            $input['city'] = (! empty($input['city'])) ? $input['city'] : null;
            $supplier->update($input);

        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }

    public function getSuppliers()
    {
        return Supplier::get()->pluck('name', 'id')->sort();
    }

    public function getSupplierAssociatedData($supplierId)
    {
        $supplierData = Supplier::with(['purchasemedicines'])->find($supplierId);
        return $supplierData;
    }

    public function createNotification($input)
    {
        try {
            $receptionists = Receptionist::pluck('user_id', 'id')->toArray();

            $userIds = [];
            foreach ($receptionists as $key => $userId) {
                $userIds[$userId] = Notification::NOTIFICATION_FOR[Notification::RECEPTIONIST];
            }
            $users = getAllNotificationUser($userIds);

            foreach ($users as $key => $notification) {
                if (isset($key)) {
                    addNotification([
                        Notification::NOTIFICATION_TYPE['Supplier'],
                        $key,
                        $notification,
                        $input['name'].' added as a supplier.',
                    ]);
                }
            }
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
