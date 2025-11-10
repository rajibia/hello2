<?php

namespace App\Repositories;

use App\Models\OpdProvisionalDiagnosis;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class OpdDiagnosisRepository
 *
 * @version September 8, 2020, 11:46 am UTC
 */
class OpdProceduralDiagnosisRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'opd_patient_department_id',
        // 'report_type',
        // 'report_date',
        'description',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return OpdProvisionalDiagnosis::class;
    }

    public function store($input)
    {
        try {
            $opdDiagnosis = $this->create($input);
            if (isset($input['file']) && ! empty($input['file'])) {
                $opdDiagnosis->addMedia($input['file'])->toMediaCollection(OpdDiagnosis::OPD_PROCEDURAL_DIAGNOSIS_PATH,
                    config('app.media_disc'));
            }
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateOpdDiagnosis($input, $opdDiagnosisId)
    {
        try {
            $opdDiagnosis = $this->update($input, $opdDiagnosisId);
            if (isset($input['file']) && ! empty($input['file'])) {
                $opdDiagnosis->clearMediaCollection(OpdProvisionalDiagnosis::OPD_DIAGNOSIS_PATH);
                $opdDiagnosis->addMedia($input['file'])->toMediaCollection(OpdDiagnosis::OPD_PROCEDURAL_DIAGNOSIS_PATH,
                    config('app.media_disc'));
            }
            // if ($input['avatar_remove'] == 1 && isset($input['avatar_remove']) && ! empty($input['avatar_remove'])) {
            //     removeFile($opdDiagnosis, OpdDiagnosis::OPD_DIAGNOSIS_PATH);
            // }
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function deleteOpdDiagnosis($opdDiagnosisId)
    {
        try {
            $opdDiagnosis = $this->find($opdDiagnosisId);
            $opdDiagnosis->clearMediaCollection(OpdProvisionalDiagnosis::OPD_PROCEDURAL_DIAGNOSIS_PATH);
            $this->delete($opdDiagnosisId);
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
