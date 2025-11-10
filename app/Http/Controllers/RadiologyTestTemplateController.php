<?php

namespace App\Http\Controllers;

use App\Models\IpdPatientDepartment;
use App\Models\OpdPatientDepartment;
use App\Models\RadiologyParameterItem;
use App\Models\RadiologyTestTemplate;
use App\Models\Charge;
use App\Models\RadiologyTest;
use App\Repositories\InvoiceRepository;
use App\Repositories\RadiologyTestRepository;
use App\Repositories\RadiologyTestTemplateRepository;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateRadiologyTestTemplateRequest;
use Flash;

class RadiologyTestTemplateController extends AppBaseController
{
    /** @var RadiologyTestTemplateRepository */
    private $radiologyTestTemplateRepository;
    private $invoiceRepository;

    public function __construct(RadiologyTestTemplateRepository $radiologyTestRepo, InvoiceRepository $invoiceRepo)
    {
        $this->radiologyTestTemplateRepository = $radiologyTestRepo;
        $this->invoiceRepository = $invoiceRepo;
    }

    public function index()
    {
        // Always show dynamic templates
        return view('radiology_tests_template.dynamic_index');
    }

    public function createTemplate(Request $request)
    {
        return view('radiology_tests_template.create');
    }

    public function previewTemplate(Request $request)
    {
        $previewData = session('radiology_template_preview');

        if (!$previewData) {
            Flash::error('No preview data found. Please create a template first.');
            return redirect()->route('radiology.test.templates.create');
        }

        return view('radiology_tests_template.preview', compact('previewData'));
    }

    public function storeTemplate(Request $request)
    {
        $input = $request->all();

        $input['standard_charge'] = $input['standard_charge'];
        $input['subcategory'] = !empty($input['subcategory']) ? $input['subcategory'] : null;
        $input['method'] = !empty($input['method']) ? $input['method'] : null;
        $input['report_days'] = !empty($input['report_days']) ? $input['report_days'] : null;
        $input['patient_id'] = !empty($input['patient_id']) ? $input['patient_id'] : null;

        if (isset($input['parameter_id']) && $input['parameter_id']) {
            foreach ($input['parameter_id'] as $key => $value) {
                if ($input['parameter_id'][$key] == null) {
                    Flash::error(__('messages.new_change.parameter_name_required'));
                    return redirect()->back();
                }
            }
        }

        $this->radiologyTestTemplateRepository->store($input);

        Flash::success(__('messages.radiology_tests') . ' ' . __('messages.common.saved_successfully'));

        return redirect(route('radiology.test.template.index'));
    }

    public function edit(RadiologyTestTemplate $radiologyTest)
    {
        $data = $this->radiologyTestTemplateRepository->getRadiologyAssociatedData();

        $patient_id = $radiologyTest->patient_id;
        $opd_id = $radiologyTest->opd_id;
        $ipd_id = $radiologyTest->ipd_id;
        $status = $radiologyTest->status;

        if ($opd_id != '') {
            $patient_id = OpdPatientDepartment::where('id', $opd_id)->pluck('patient_id')->first();
        }
        if ($ipd_id != '') {
            $patient_id = IpdPatientDepartment::where('id', $ipd_id)->pluck('patient_id')->first();
        }

        $patients = $this->radiologyTestTemplateRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');

        $parameterList = $this->radiologyTestTemplateRepository->getParameterDataList();
        $radiologyParameterItems = $this->radiologyTestTemplateRepository->getParameterItemData($radiologyTest->id);

        return view('radiology_tests_template.edit', compact('radiologyTest', 'data', 'parameterList', 'radiologyParameterItems', 'patients', 'patient_id', 'opd_id', 'ipd_id', 'opds', 'ipds', 'status'));
    }

    public function update(RadiologyTestTemplate $radiologyTest, UpdateRadiologyTestTemplateRequest $request)
    {
        $input = $request->all();
        $input['standard_charge'] = $input['standard_charge'];
        $input['subcategory'] = !empty($input['subcategory']) ? $input['subcategory'] : null;
        $input['method'] = !empty($input['method']) ? $input['method'] : null;
        $input['report_days'] = !empty($input['report_days']) ? $input['report_days'] : null;
        $input['patient_id'] = !empty($input['patient_id']) ? $input['patient_id'] : null;

        if ($input['parameter_id']) {
            foreach ($input['parameter_id'] as $key => $value) {
                if ($input['parameter_id'][$key] == null) {
                    Flash::error(__('messages.new_change.parameter_name_required'));
                    return redirect()->back();
                }
            }
        }

        $this->radiologyTestTemplateRepository->update($input, $radiologyTest);
        Flash::success(__('messages.radiology_tests') . ' ' . __('messages.common.updated_successfully'));

        return redirect(route('radiology.test.template.index'));
    }

    public function destroy(RadiologyTestTemplate $radiologyTest)
    {
        $radiologyTest->parameterItems()->delete();
        $radiologyTest->delete();

        return $this->sendSuccess(__('messages.radiology_tests') . ' ' . __('messages.common.deleted_successfully'));
    }

    public function getStandardCharge($id)
    {
        $standardCharges = Charge::where('charge_category_id', $id)->value('standard_charge');

        return $this->sendResponse($standardCharges, 'StandardCharge retrieved successfully.');
    }

    public function radiologyTestExport()
    {
        return Excel::download(new RadiologyTestExport, 'radiology-tests-' . time() . '.xlsx');
    }

    public function showModal(RadiologyTestTemplate $radiologyTest)
    {
        $radiologyTest->load(['radiologycategory', 'chargecategory']);
        $radiologyParameterItems = RadiologyParameterItem::with('radiologyTest', 'radiologyParameter.radiologyUnit')->whereRadiologyId($radiologyTest->id)->get();

        $currency = $radiologyTest->currency_symbol ? strtoupper($radiologyTest->currency_symbol) : strtoupper(getCurrentCurrency());
        $radiologyTest = [
            'test_name' => $radiologyTest->test_name,
            'short_name' => $radiologyTest->short_name,
            'test_type' => $radiologyTest->test_type,
            'radiology_category_name' => $radiologyTest->radiologycategory->name,
            'report_days' => $radiologyTest->report_days,
            'standard_charge' => checkValidCurrency($radiologyTest->currency_symbol ?? getCurrentCurrency()) ? moneyFormat($radiologyTest->standard_charge, $currency) : number_format($radiologyTest->standard_charge) . '' . ($radiologyTest->currency_symbol ? getSymbols($radiologyTest->currency_symbol) : getCurrencySymbol()),
            'subcategory' => $radiologyTest->subcategory,
            'method' => $radiologyTest->method,
            'charge_category_name' => $radiologyTest->chargecategory->name,
            'radiologyParameterItems' => $radiologyParameterItems,
            'created_at' => $radiologyTest->created_at,
            'updated_at' => $radiologyTest->updated_at,
        ];

        return $this->sendResponse($radiologyTest, 'Radiology Test Retrieved Successfully.');
    }

    public function getRadiologyParameter($id)
    {
        $data = [];
        $data['parameter'] = RadiologyParameter::with('radiologyUnit')->whereId($id)->first();

        return $this->sendResponse($data, 'retrieved');
    }

    public function convertToPDF($id)
    {
        $data = [];
        $data['logo'] = $this->radiologyTestTemplateRepository->getSettingList();
        $data['radiologyTest'] = RadiologyTestTemplate::with(['radiologycategory', 'chargecategory'])->where('id', $id)->first();
        $data['radiologyParameterItems'] = RadiologyParameterItem::with('radiologyTest', 'radiologyParameter.radiologyUnit')->whereRadiologyId($id)->get();

        return view('radiology_tests_template.radiology_test_pdf', compact('data'));
    }

    public function show(RadiologyTestTemplate $radiologyTest)
    {
        $radiologyParameterItems = RadiologyParameterItem::with('radiologyTest', 'radiologyParameter.radiologyUnit')->whereRadiologyId($radiologyTest->id)->get();

        return view('radiology_tests_template.show', compact('radiologyTest', 'radiologyParameterItems'));
    }

    public function getRadiologyTemplateDetails($id)
    {
        $radiologyTemplateDetails = RadiologyTestTemplate::whereId($id)->first();

        return [
            'report_days' => $radiologyTemplateDetails->report_days,
            'report_date' => date('Y-m-d', strtotime('+' . $radiologyTemplateDetails->report_days . 'days')),
            'amount' => $radiologyTemplateDetails->standard_charge,
        ];
    }
}
