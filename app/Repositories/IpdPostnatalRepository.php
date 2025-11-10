<?php

namespace App\Repositories;

use App\Models\IpdPostnatalHistory;
use Exception;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class IpdPostnatalRepository
 */
class IpdPostnatalRepository extends BaseRepository
{
    /**
     * Fields that can be searched in the repository
     *
     * @var array
     */
    protected $fieldSearchable = [
        'patient_id',
        'labour_time',	
        'delivery_time',
        'routine_question',
        'general_remark',
    ];

    /**
     * Get searchable fields array
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Define the model
     *
     * @return string
     */
    public function model()
    {
        return IpdPostnatalHistory::class;
    }

    /**
     * Store a newly created postnatal record in storage.
     *
     * @param array $input
     * @return bool
     */
    public function store($input)
    {
        try {
            // Create a new postnatal record
            IpdPostnatalHistory::create([
                'patient_id' => $input['patient_id'],
                'labour_time' => $input['labour_time'] ?? null,
                'delivery_time' => $input['delivery_time'] ?? null,
                'routine_question' => $input['routine_question'] ?? null,
                'general_remark' => $input['general_remark'] ?? null,
            ]);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $input
     * @param int $id
     * @return bool
     */
    // public function update($input, $id)
    // {
    //     try {
    //         // Find the antenatal record by ID
    //         $antenatal = $this->find($id);

    //         // Check if the record exists
    //         if (empty($antenatal)) {
    //             throw new Exception(__('messages.antenatal.not_found'));
    //         }

    //         // Update antenatal data
    //         $antenatal->update($input);

    //         return true;
    //     } catch (Exception $e) {
    //         throw new UnprocessableEntityHttpException($e->getMessage());
    //     }
    // }

    /**
     * Delete the specified resource from storage.
     *
     * @param int $id
     * @return bool
     */
    // public function delete($id)
    // {
    //     try {
    //         // Find the antenatal record by ID
    //         $antenatal = $this->find($id);

    //         // Check if the record exists
    //         if (empty($antenatal)) {
    //             throw new Exception(__('messages.antenatal.not_found'));
    //         }

    //         // Delete the antenatal record
    //         $antenatal->delete();

    //         return true;
    //     } catch (Exception $e) {
    //         throw new UnprocessableEntityHttpException($e->getMessage());
    //     }
    // }
}
