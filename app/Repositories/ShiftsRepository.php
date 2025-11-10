<?php

namespace App\Repositories;

use App\Models\Shift;
use Exception;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class ShiftsRepository
 */
class ShiftsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'shift_name',
        'shift_start',
        'shift_end',
        'break_duration',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Shift::class;
    }
    
    public function store($input)
    {
        try {
            // Create a new shift using the input data
            Shift::create([
                'shift_name' => $input['shift_name'],
                'shift_start' => $input['shift_start'],
                'shift_end' => $input['shift_end'],
                'break_duration' => $input['break_duration'],
            ]);

            return true; 
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function update($input, $id)
    {
        try {
            // Find the shift by ID
            $shift = $this->find($id);

            // Check if the shift exists
            if (empty($shift)) {
                throw new Exception(__('messages.shift.shift') . ' ' . __('messages.common.not_found'));
            }

            // Update shift data
            $shift->update([
                'shift_name' => $input['shift_name'],
                'shift_start' => $input['shift_start'],
                'shift_end' => $input['shift_end'],
                'break_duration' => $input['break_duration'],
            ]);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            // Find the shift by ID
            $shift = $this->find($id);

            // Check if the shift exists
            if (empty($shift)) {
                throw new Exception(__('messages.shift.shift') . ' ' . __('messages.common.not_found'));
            }

            // Delete the shift
            $shift->delete();

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

}
