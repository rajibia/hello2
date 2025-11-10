<?php

namespace App\Repositories;

use App\Models\IpdProvisionalDiagnosis;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class IpdDiagnosisRepository
 *
 * @version September 8, 2020, 11:46 am UTC
 */
class IpdProceduralDiagnosisRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ipd_patient_department_id',
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
        return IpdProvisionalDiagnosis::class;
    }

    public function store($input)
    {
        try {
            $ipdDiagnosis = $this->create($input);
            if (isset($input['file']) && ! empty($input['file'])) {
                $ipdDiagnosis->addMedia($input['file'])->toMediaCollection(IpdDiagnosis::IPD_PROCEDURAL_DIAGNOSIS_PATH,
                    config('app.media_disc'));
            }
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateIpdDiagnosis($input, $ipdDiagnosisId)
    {
        try {
            $ipdDiagnosis = $this->update($input, $ipdDiagnosisId);
            if (isset($input['file']) && ! empty($input['file'])) {
                $ipdDiagnosis->clearMediaCollection(IpdProvisionalDiagnosis::IPD_DIAGNOSIS_PATH);
                $ipdDiagnosis->addMedia($input['file'])->toMediaCollection(IpdDiagnosis::IPD_PROCEDURAL_DIAGNOSIS_PATH,
                    config('app.media_disc'));
            }
            // if ($input['avatar_remove'] == 1 && isset($input['avatar_remove']) && ! empty($input['avatar_remove'])) {
            //     removeFile($ipdDiagnosis, IpdDiagnosis::IPD_DIAGNOSIS_PATH);
            // }
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function deleteIpdDiagnosis($ipdDiagnosisId)
    {
        try {
            $ipdDiagnosis = $this->find($ipdDiagnosisId);
            $ipdDiagnosis->clearMediaCollection(IpdProvisionalDiagnosis::IPD_PROCEDURAL_DIAGNOSIS_PATH);
            $this->delete($ipdDiagnosisId);
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
