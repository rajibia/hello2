<?php

namespace App\Repositories;

use App\Models\PreviousObstetricHistory;
use Exception;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class OpdObstetricRepository
 */
class OpdObstetricRepository extends BaseRepository
{
    /**
     * Fields that can be searched in the repository
     *
     * @var array
     */
    protected $fieldSearchable = [
        'patient_id',
        'place_of_delivery',
        'duration_of_pregnancy',
        'complication_in_pregnancy_or_puerperium',
        'birth_weight',
        'gender',
        'infant_feeding',
        'birth_status',
        'alive_or_dead_date',
        'previous_medical_history',
        'special_instruction',
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
        return PreviousObstetricHistory::class;
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
            PreviousObstetricHistory::create([
                'patient_id' => $input['patient_id'],
                'place_of_delivery' => $input['place_of_delivery'] ?? null,
                'duration_of_pregnancy' => $input['duration_of_pregnancy'] ?? null,
                'complication_in_pregnancy_or_puerperium' => $input['complications'] ?? null,
                'birth_weight' => $input['birth_weight'] ?? null,
                'gender' => $input['gender'] ?? null,
                'infant_feeding' => $input['infant_feeding'] ?? null,
                'birth_status' => $input['birth_status'] ?? null,
                'alive_or_dead_date' => $input['alive_or_dead_date'] ?? null,
                'previous_medical_history' => $input['previous_medical_history'] ?? null,
                'special_instruction' => $input['special_instruction'] ?? null,
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
