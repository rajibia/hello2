<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDeathReportRequest;
use App\Http\Requests\UpdateDeathReportRequest;
use App\Models\BirthReport;
use App\Models\DeathReport;
use App\Models\PatientCase;
use App\Repositories\DeathReportRepository;
use Carbon\Carbon;

class DeathReportController extends AppBaseController
{
    /** @var DeathReportRepository */
    private $deathReportRepository;

    public function __construct(DeathReportRepository $deathReportRepo)
    {
        $this->deathReportRepository = $deathReportRepo;
    }

    public function index()
    {
        $cases = $this->deathReportRepository->getCases();
        $doctors = $this->deathReportRepository->getDoctors();

        return view('death_reports.index', compact('cases', 'doctors'));
    }

    public function store(CreateDeathReportRequest $request)
    {
        $input = $request->all();
        
        // Log input for debugging
        \Log::info('Request Data:', $input);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            $input['attachments'] = $request->file('attachments')->store('death_reports/attachments', 'public');
        }

        // Parse and format the death date
        $input['date'] = Carbon::parse($input['date'])->format('Y-m-d H:i:s');

        // Retrieve the patient's birth date
        $patientId = BirthReport::whereCaseId($input['case_id'])->first();

        if (empty($patientId)) {
            \Log::error('Patient Birth Date not found for Case ID:', ['case_id' => $input['case_id']]);
            return $this->sendError(__('messages.birth_report.patient_birth_date_not_found'));
        }

        $birthDate = Carbon::parse($patientId->date);
        $deathDate = Carbon::parse($input['date']);

        \Log::info('Birth Date:', ['birth_date' => $birthDate]);
        \Log::info('Death Date:', ['death_date' => $deathDate]);

        if (!empty($birthDate) && $deathDate < $birthDate) {
            return $this->sendError(
                __('messages.birth_report.data_should_not_be_smaller_than_patient_birth_date')
                . ' Birth Date: ' . $birthDate->toDateString() 
                . ', Death Date: ' . $deathDate->toDateString()
            );
        }

        // Store the death report
        $deathReport = $this->deathReportRepository->store($input);

        return $this->sendSuccess(__('messages.death_report.death_report').' '.__('messages.common.saved_successfully'));
    }

    public function show(DeathReport $deathReport)
    {
        $cases = $this->deathReportRepository->getCases();
        $doctors = $this->deathReportRepository->getDoctors();

        return view('death_reports.show')->with([
            'deathReport' => $deathReport, 'cases' => $cases, 'doctors' => $doctors,
        ]);
    }

    public function edit(DeathReport $deathReport)
    {
        if (checkRecordAccess($deathReport->doctor_id)) {
            return $this->sendError(__('messages.death_report.death_report').' '.__('messages.common.not_found'));
        } else {
            return $this->sendResponse($deathReport, 'Death Report retrieved successfully.');
        }
    }

    public function update(DeathReport $deathReport, UpdateDeathReportRequest $request)
    {
        $input = $request->all();
        $patientId = PatientCase::with('patient.patientUser')->whereCaseId($input['case_id'])->first();
        $birthDate = $patientId->patient->patientUser->dob;
        $deathDate = Carbon::parse($input['date'])->toDateString();

        if (! empty($birthDate) && $deathDate < $birthDate) {
            return $this->sendError(__('messages.death_report.data_should_not_be_smaller_than_patient_birth_date'));
        }

        $deathReport = $this->deathReportRepository->update($request->all(), $deathReport);

        return $this->sendSuccess(__('messages.death_report.death_report').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(DeathReport $deathReport)
    {
        if (checkRecordAccess($deathReport->doctor_id)) {
            return $this->sendError(__('messages.death_report.death_report').' '.__('messages.common.not_found'));
        } else {
            $this->deathReportRepository->delete($deathReport->id);

            return $this->sendSuccess(__('messages.death_report.death_report').' '.__('messages.common.deleted_successfully'));
        }
    }
}
