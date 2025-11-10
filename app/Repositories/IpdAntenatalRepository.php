<?php

namespace App\Repositories;

use App\Models\Antenatal;
use Exception;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class IpdAntenatalRepository
 */
class IpdAntenatalRepository extends BaseRepository
{
    /**
     * Fields that can be searched in the repository
     *
     * @var array
     */
    protected $fieldSearchable = [
        'ipd_id',
        'date',
        'condition',
        'blood_pressure',
        'temperature',
        'weight',
        'fundal_height',
        'fetal_heart_rate',
        'medications',
        'remarks',
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
        return Antenatal::class;
    }

    /**
     * Store a newly created antenatal record in storage.
     *
     * @param array $input
     * @return bool
     */
    public function store($input)
    {
        try {
            // Create a new antenatal record
            Antenatal::create([
                'patient_id' => $input['patient_id'],
                'bleeding' => $input['bleeding'] ?? null,
                'headache' => $input['headache'] ?? null,
                'pain' => $input['pain'] ?? null,
                'constipation' => $input['constipation'] ?? null,
                'urinary_symptoms' => $input['urinary_symptoms'] ?? null,
                'vomiting' => $input['vomiting'] ?? null,
                'cough' => $input['cough'] ?? null,
                'vaginal_discharge' => $input['vaginal_discharge'] ?? null,
                'oedema' => $input['oedema'] ?? null,
                'haemorrhoids' => $input['haemorrhoids'] ?? null,
                'date' => $input['date'],
                'condition' => $input['condition'] ?? null,
                'special_findings_and_remark' => $input['special_findings_and_remark'] ?? null,
                'pelvic_examination' => $input['pelvic_examination'] ?? null,
                'sp' => $input['sp'] ?? null,
                'uter_size' => $input['uter_size'] ?? null,
                'uterus_size' => $input['uterus_size'] ?? null,
                'presentation_position' => $input['presentation_position'] ?? null,
                'presenting_part_to_brim' => $input['presenting_part_to_brim'] ?? null,
                'foetal_heart' => $input['foetal_heart'] ?? null,
                'blood_pressure' => $input['blood_pressure'] ?? null,
                'antenatal_oedema' => $input['antenatal_oedema'] ?? null,
                'urine_sugar' => $input['urine_sugar'] ?? null,
                'urine_albumin' => $input['urine_albumin'] ?? null,
                'antenatal_weight' => $input['antenatal_weight'] ?? null,
                'remark' => $input['remark'] ?? null,
                'next_visit' => $input['next_visit'] ?? null,
                'previous_antenatal_details' => $input['previous_antenatal_details'] ?? null,
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
    public function update($input, $id)
    {
        try {
            // Find the antenatal record by ID
            $antenatal = $this->find($id);

            // Check if the record exists
            if (empty($antenatal)) {
                throw new Exception(__('messages.antenatal.not_found'));
            }

            // Update antenatal data
            $antenatal->update($input);

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        try {
            // Find the antenatal record by ID
            $antenatal = $this->find($id);

            // Check if the record exists
            if (empty($antenatal)) {
                throw new Exception(__('messages.antenatal.not_found'));
            }

            // Delete the antenatal record
            $antenatal->delete();

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
