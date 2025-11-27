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
    /** @var GeneratePatientIdCardRepository */
    private $GeneratePatientIdCardRepositorie;

    public function __construct(GeneratePatientIdCardRepository $GeneratePatientIdCardRepositorie)
    {
        $this->GeneratePatientIdCardRepositorie = $GeneratePatientIdCardRepositorie;
    }

    public function index()
    {
        $templates = $this->GeneratePatientIdCardRepositorie->getTemplates();
        $patients = $this->GeneratePatientIdCardRepositorie->getPatients();

        return view('generate_patient_id_card.index', compact('templates','patients'));
    }

    public function store(CreatePatientIdCardRequest $request)
    {
        $input = $request->all();
        $this->GeneratePatientIdCardRepositorie->store($input);

        return $this->sendSuccess(__('messages.patient_id_card.patient_id_card').' '.__('messages.common.saved_successfully'));
    }

    public function show($uniqueId)
    {
        $patients = Patient::with(['patientUser','address','idCardTemplate'])
            ->where('patient_unique_id',$uniqueId)
            ->first();

        return $this->sendResponse($patients, 'Data retrieved successfully.');
    }

    public function destroy($id)
    {
        Patient::find($id)->update(['template_id' => null]);

        return $this->sendSuccess(__('messages.patient_id_card.patient_id_card').' '.__('messages.common.deleted_successfully'));
    }

    public function downloadIdCard($id)
    {
        $patientIdCardData = Patient::with(['patientUser','address','idCardTemplate'])->find($id);
        $patientIdCardTemplateData = PatientIdCardTemplate::find($patientIdCardData->idCardTemplate->id);

        $url = route('qrcode.patient.show', $patientIdCardData->patient_unique_id);
        $qrCode = QrCode::size(90)->generate($url);

        /* ---------------------------
           SAFE IMAGE DOWNLOAD METHOD
        ---------------------------- */
        $imgUrl = $patientIdCardData->patientUser->image_url;

        $data = [];

        if (!$imgUrl) {
            // fallback image (blank profile)
            $data['profile'] = null;
        } else {

            $arrUrl = explode('/', trim($imgUrl));

            if (isset($arrUrl[2]) && $arrUrl[2] == "ui-avatars.com") {
                $avatarUrl = "https://ui-avatars.com/api/?name=" .
                    urlencode($patientIdCardData->patientUser->full_name) .
                    "&size=100&rounded=true&color=fff&background=fc6369";
            } else {
                $avatarUrl = $imgUrl;
            }

            $avatarData = $this->safeDownload($avatarUrl);

            // If download failed, avoid breaking PDF
            $data['profile'] = $avatarData ? base64_encode($avatarData) : null;
        }

        $pdf = PDF::loadView(
            'generate_patient_id_card.patient_id_card_pdf',
            compact('patientIdCardData','patientIdCardTemplateData','qrCode','data')
        );

        return $pdf->download($patientIdCardData->patientUser->full_name.'-'.$patientIdCardData->id.'.pdf');
    }

    /**
     * Safe file downloader (supports SSL + Catches Errors)
     */
    private function safeDownload($url)
    {
        if (!$url) return null;

        try {
            $context = stream_context_create([
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ]
            ]);

            $data = @file_get_contents($url, false, $context);

            return $data ?: null;

        } catch (\Exception $e) {
            return null;
        }
    }

    public function generateQrCode($uniqueId)
    {
        $url = route('qrcode.patient.show', $uniqueId);
        $qrCode = QrCode::size(90)->generate($url);

        return $qrCode;
    }
}
