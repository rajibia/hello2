<?php

namespace App\Repositories;

use App\Models\Scan;
use Arr;
use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Validator;
use Spatie\MediaLibrary\Support\MediaStream;
use Illuminate\Support\Str;
use App\Models\Accountant;
use App\Models\Notification;
use App\Models\Receptionist;
use App\Models\User;

/**
 * Class ScanRepository
 *
 * @version February 22, 2024, 9:01 am UTC
 */
class ScanRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'insurance_name',
        'flag',
        'tariff',
        'non_insured_amount',
        'topup',
        'gdrg_code',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Scan::class;
    }

    /*
    public function store($input)
    {
        // add scan_id and name
        $scan = Scan::create($input);

        return true;
    }

    public function update($scan, $input)
    {
    
        $scan->update($input);

        return true;
    }

    public function delete($scanId)
    {
        try {
            $scan = Scan::find($scanId);
            $scan->delete();

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
*/
    public function createNotification()
    {
        try {
            $ownerType = [Receptionist::class, Accountant::class];
            $userIds = User::whereIn('owner_type', $ownerType)->pluck('owner_type', 'id')->toArray();
            $adminUser = User::role('Admin')->first();
            $allUsers = $userIds + [$adminUser->id => ''];
            $users = getAllNotificationUser($allUsers);

            foreach ($users as $id => $ownerType) {
                addNotification([
                    Notification::NOTIFICATION_TYPE['Scan'],
                    $id,
                    Notification::NOTIFICATION_FOR[User::getOwnerType($ownerType)],
                    'New scan from insurance has been added.',
                ]);
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

}
