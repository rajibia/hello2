<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MaternityPatientDepartment;
use App\Repositories\MaternityPatientDepartmentRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaternityController extends Controller
{
    /** @var MaternityPatientDepartmentRepository */
    private $maternityPatientDepartmentRepository;

    public function __construct(MaternityPatientDepartmentRepository $maternityPatientDepartmentRepo)
    {
        $this->maternityPatientDepartmentRepository = $maternityPatientDepartmentRepo;
    }

    public function index()
    {
        return view('maternity_patient_list.index');
    }

    public function show(MaternityPatientDepartment $maternityPatientDepartment)
    {
        if (checkRecordAccess($maternityPatientDepartment->patient_id)) {
            return view('errors.404');
        } else {
            return view('maternity_patient_list.show', compact('maternityPatientDepartment'));
        }
    }
}
