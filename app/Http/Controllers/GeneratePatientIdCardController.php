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
        try {
            $patientIdCardData = Patient::with(['patientUser','address','idCardTemplate'])->findOrFail($id);
            $patientIdCardTemplateData = PatientIdCardTemplate::findOrFail($patientIdCardData->template_id);

            $url = route('qrcode.patient.show', $patientIdCardData->patient_unique_id);
            $qrCode = QrCode::size(90)->generate($url);

            /* ---------------------------
               OPTIMIZED IMAGE DOWNLOAD WITH CACHING
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

                // Use caching to speed up downloads
                $cacheKey = 'patient_avatar_' . md5($avatarUrl);
                $avatarData = \Cache::remember($cacheKey, 3600, function() use ($avatarUrl) {
                    return $this->safeDownload($avatarUrl);
                });

                // If download failed, avoid breaking PDF
                $data['profile'] = $avatarData ? base64_encode($avatarData) : null;
            }

            $pdf = PDF::loadView(
                'generate_patient_id_card.patient_id_card_pdf',
                compact('patientIdCardData','patientIdCardTemplateData','qrCode','data')
            );

            // Configure PDF rendering options
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            $pdf->setOption('defaultFont', 'DejaVu Sans');
            $pdf->setOption('dpi', 150);
            $pdf->setOption('fontSubsetting', false);

            $filename = $patientIdCardData->patientUser->full_name . '-' . $patientIdCardData->id . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Patient ID Card PDF generation error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()->with('error', __('messages.something_went_wrong'));
        }
    }

    /**
     * Safe file downloader (supports SSL + Catches Errors)
     */
    private function safeDownload($url, $timeout = 5)
    {
        if (!$url) return null;

        try {
            $context = stream_context_create([
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ],
                "http" => [
                    "timeout" => $timeout,
                    "method" => "GET",
                ]
            ]);

            $data = @file_get_contents($url, false, $context);

            return $data ?: null;

        } catch (\Exception $e) {
            \Log::warning('Failed to download image: ' . $e->getMessage());
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
