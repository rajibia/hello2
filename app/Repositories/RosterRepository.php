<?php

namespace App\Repositories;

use App\Models\Roster;
use App\Models\Shift;
use Exception;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class RosterRepository
 */
class RosterRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'shift_id',
        'start_date',
        'end_date',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Roster::class;
    }

    public function getAllShifts()
    {
        return Shift::all();
    }
    
    public function store($input)
    {
        try {
            // Create a new roster using the input data
            Roster::create([
                'shift_id' => $input['shift_id'],
                'start_date' => $input['start_date'],
                'end_date' => $input['end_date'],
            ]);

            return true; 
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateRoster($id, $input)
    {
        try {
            // Find the roster by ID
            $roster = $this->find($id);

            // Update the roster fields
            $roster->update([
                'shift_id' => $input['shift_id'],
                'start_date' => $input['start_date'],
                'end_date' => $input['end_date'],
            ]);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            // Find the roster by ID
            $roster = $this->find($id);

            // Check if the roster exists
            if (empty($roster)) {
                throw new Exception(__('messages.roster.roster') . ' ' . __('messages.common.not_found'));
            }

            // Delete the roster
            $roster->delete();

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
