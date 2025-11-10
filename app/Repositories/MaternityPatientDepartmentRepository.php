<?php

namespace App\Repositories;

use App\Models\MaternityPatientDepartment;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class MaternityPatientDepartmentRepository
 * @package App\Repositories
 * @version December 16, 2024, 12:00 pm UTC
 */
class MaternityPatientDepartmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'patient_id',
        'doctor_id',
        'admission_date',
        'discharge_date',
        'status',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MaternityPatientDepartment::class;
    }

    /**
     * Get all maternity patients
     *
     * @return Collection
     */
    public function getAllMaternityPatients()
    {
        return $this->model->with(['patient', 'doctor'])->get();
    }

    /**
     * Get maternity patient by ID
     *
     * @param int $id
     * @return MaternityPatientDepartment
     */
    public function getMaternityPatientById($id)
    {
        return $this->model->with(['patient', 'doctor'])->findOrFail($id);
    }
}
