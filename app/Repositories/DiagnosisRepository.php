<?php

namespace App\Repositories;

use App\Models\Diagnosis;
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
 * Class DiagnosisRepository
 *
 * @version February 22, 2024, 9:01 am UTC
 */
class DiagnosisRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'insurance_name',
        'grouping',
        'tariff',
        'speciality_code',
        'non_insured_amount',
        'topup',
        'gdrg_code',
        'icd_10_code',

    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Diagnosis::class;
    }

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
                    Notification::NOTIFICATION_TYPE['Diagnosis'],
                    $id,
                    Notification::NOTIFICATION_FOR[User::getOwnerType($ownerType)],
                    'New diagnosis from insurance has been added.',
                ]);
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

}
