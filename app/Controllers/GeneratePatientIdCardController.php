<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Http\Requests\CreatePatientIdCardRequest;
use App\Repositories\GeneratePatientIdCardRepository;
use App\Models\PatientIdCardTemplate;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GeneratePatientIdCardController extends AppBaseController
{
    /**
     * @var GeneratePatientIdCardRepository
     */
    private $generatePatientIdCardRepository;

    public function __construct(GeneratePatientIdCardRepository $generatePatientIdCardRepository)
    {
        $this->generatePatientIdCardRepository = $generatePatientIdCardRepository;
    }

    /**
     * Display a listing of templates and patients.
     */
    public function index()
    {
        $templates = $this->generatePatientIdCardRepository->getTemplates();
        $patients = $this->generatePatientIdCardRepository->getPatients();

        return view('generate_patient_id_card.index', compact('templates', 'patients'));
    }

    /**
     * Store a newly created patient ID card.
     */
    public function store(CreatePatientIdCardRequest $request)
    {
        $input = $request->all();
        $this->generatePatientIdCardRepository->store($input);

        return $this->sendSuccess(__('messages.patient_id_card.patient_id_card') . ' ' . __('messages.common.saved_successfully'));
    }

    /**
     * Display the specified patient data by unique ID.
     */
    public function show($uniqueId)
    {
        $patient = Patient::with(['patientUser', 'address', 'idCardTemplate'])
            ->where('patient_unique_id', $uniqueId)
            ->first();

        if (!$patient) {
            return $this->sendError('Patient not found.');
        }

        return $this->sendResponse($patient, 'Data retrieved successfully.');
    }

    /**
     * Remove the patient ID card (unlink template).
     */
    public function destroy($id)
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return $this->sendError('Patient not found.');
        }

        $patient->update(['template_id' => null]);

        return $this->sendSuccess(__('messages.patient_id_card.patient_id_card') . ' ' . __('messages.common.deleted_successfully'));
    }

    /**
     * Generate and download the patient ID card as PDF.
     */
    public function downloadIdCard($id)
    {
        $patient = Patient::with(['patientUser', 'address', 'idCardTemplate'])->find($id);

        if (!$patient) {
            abort(404, 'Patient not found.');
        }

        $template = $patient->idCardTemplate;
        if (!$template) {
            abort(404, 'Patient ID Card Template not found.');
        }

        // Generate QR Code
        $url = route('qrcode.patient.show', $patient->patient_unique_id);
        $qrCode = QrCode::size(90)->generate($url);

        // Handle profile image
        $defaultAvatarPath = public_path('images/default-avatar.png'); // Make sure this exists
        $imgUrl = $patient->patientUser->image_url ?? '';

        $avatarData = null;

        if ($imgUrl) {
            if (str_contains($imgUrl, 'ui-avatars.com')) {
                // Use local default avatar instead of fetching ui-avatars
                $avatarData = file_exists($defaultAvatarPath) ? file_get_contents($defaultAvatarPath) : null;
            } else {
                // Try to fetch local image
                $localPath = public_path(parse_url($imgUrl, PHP_URL_PATH));
                if (file_exists($localPath)) {
                    $avatarData = file_get_contents($localPath);
                } else {
                    $avatarData = file_exists($defaultAvatarPath) ? file_get_contents($defaultAvatarPath) : null;
                }
            }
        } else {
            $avatarData = file_exists($defaultAvatarPath) ? file_get_contents($defaultAvatarPath) : null;
        }

        $data['profile'] = $avatarData ? base64_encode($avatarData) : null;

        // Increase execution time for large PDFs
        ini_set('max_execution_time', 180);

        // Generate PDF
        $pdf = PDF::loadView('generate_patient_id_card.patient_id_card_pdf', [
            'patientIdCardData' => $patient,
            'patientIdCardTemplateData' => $template,
            'qrCode' => $qrCode,
            'data' => $data
        ])->setPaper('A4', 'portrait');

        $fileName = $patient->patientUser->full_name . '-' . $patient->id . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Generate QR Code for a given patient unique ID.
     */
    public function generateQrCode($uniqueId)
    {
        $url = route('qrcode.patient.show', $uniqueId);
        return QrCode::size(90)->generate($url);
    }
}
