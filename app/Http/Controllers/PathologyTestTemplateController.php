<?php

namespace App\Http\Controllers;

use App\Models\IpdPatientDepartment;
use App\Models\OpdPatientDepartment;
use App\Models\PathologyParameterItem;
use App\Models\PathologyTestTemplate;
use App\Models\Charge;
use App\Models\PathologyTest;
use App\Repositories\InvoiceRepository;
use App\Repositories\PathologyTestRepository;
use App\Repositories\PathologyTestTemplateRepository;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePathologyTestTemplateRequest;
use Flash;

class PathologyTestTemplateController extends AppBaseController
{
        /** @var PathologyTestTemplateRepository */
    private $pathologyTestTemplateRepository;
    private $invoiceRepository;

    public function __construct(PathologyTestTemplateRepository $pathologyTestRepo, InvoiceRepository $invoiceRepo)
    {
        $this->pathologyTestTemplateRepository = $pathologyTestRepo;
        $this->invoiceRepository = $invoiceRepo;
    }
            public function index(Request $request)
    {
        // Always show dynamic templates
        return view('pathology_tests_template.dynamic_index');
    }

    public function create(Request $request)
    {
        // Return the create view for dynamic pathology templates
        return view('pathology_tests_template.create');
    }

    public function editDynamic($id)
    {
        return view('pathology_tests_template.edit_dynamic', compact('id'));
    }

    public function updateDynamic(Request $request, $id)
    {
        try {
            $template = PathologyTestTemplate::findOrFail($id);

            // Update basic template information
            $template->update([
                'test_name' => $request->test_name,
                'short_name' => $request->short_name,
                'test_type' => $request->test_type,
                'category_id' => $request->category_id,
                'charge_category_id' => $request->charge_category_id,
                'standard_charge' => $request->standard_charge,
                'table_type' => $request->table_type,
                'layout_type' => $request->layout_type,
            ]);

            // Process form fields
            $formFields = [];
            if ($request->has('form_fields') && is_array($request->form_fields)) {
                foreach ($request->form_fields as $index => $field) {
                    $formFields[] = [
                        'id' => $index,
                        'name' => $field['name'] ?? '',
                        'label' => $field['label'] ?? '',
                        'type' => $field['type'] ?? 'text',
                        'placeholder' => $field['placeholder'] ?? '',
                        'unit' => $field['unit'] ?? '',
                        'validation' => $field['validation'] ?? '',
                        'reference_min' => $field['reference_min'] ?? '',
                        'reference_max' => $field['reference_max'] ?? '',
                        'options' => $field['options'] ?? [],
                        'group' => 'General',
                        'required' => 0,
                        'column_position' => 1,
                        'row_group' => 1,
                        'dependencies' => ['parent_field' => '', 'options_map' => []],
                        'species_config' => ['results' => '', 'species' => '', 'stages' => '', 'counts' => '', 'units' => '']
                    ];
                }
            }

            // Update form configuration
            $formConfig = $template->form_configuration ?? [];
            $formConfig['fields'] = $formFields;
            $formConfig['table_type'] = $request->table_type;
            $formConfig['layout_type'] = $request->layout_type;

            $template->update([
                'form_configuration' => $formConfig
            ]);

            return redirect()->route('pathology.test.template.index')->with('success', 'Dynamic template updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update template: ' . $e->getMessage());
        }
    }

    public function edit(PathologyTestTemplate $pathologyTest)
    {
        $data = $this->pathologyTestTemplateRepository->getPathologyAssociatedData();

        $patient_id = $pathologyTest->patient_id;
        $opd_id = $pathologyTest->opd_id;
        $ipd_id = $pathologyTest->ipd_id;
        $status = $pathologyTest->status;

        if($opd_id != '')
        {
            $patient_id = OpdPatientDepartment::where('id', $opd_id)->pluck('patient_id')->first();
        }
        if($ipd_id != '')
        {
            $patient_id = IpdPatientDepartment::where('id', $ipd_id)->pluck('patient_id')->first();
        }

        $patients = $this->pathologyTestTemplateRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');

        $parameterList = $this->pathologyTestTemplateRepository->getParameterDataList();
        $pathologyParameterItems = $this->pathologyTestTemplateRepository->getParameterItemData($pathologyTest->id);

        return view('pathology_tests_template.edit', compact('pathologyTest', 'data', 'parameterList', 'pathologyParameterItems', 'patients', 'patient_id', 'opd_id', 'ipd_id', 'opds', 'ipds', 'status'));
    }


    public function createTemplate(Request $request){
        $data = $this->pathologyTestTemplateRepository->getPathologyAssociatedData();
        $patient_id = $request->query()['ref_p_id'] ?? '';
        $opd_id = $request->query()['ref_opd_id'] ?? '';
        $ipd_id = $request->query()['ref_ipd_id'] ?? '';

        if($opd_id != '')
        {
            $patient_id = OpdPatientDepartment::where('id', $opd_id)->pluck('patient_id')->first();
        }
        if($ipd_id != '')
        {
            $patient_id = IpdPatientDepartment::where('id', $ipd_id)->pluck('patient_id')->first();
        }
        $parameterList = $this->pathologyTestTemplateRepository->getParameterDataList();
        $patients = $this->pathologyTestTemplateRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');

        return view('pathology_tests_template.create', compact('data', 'parameterList', 'opds', 'ipds', 'patients', 'patient_id', 'opd_id', 'ipd_id'));
    }

    public function storeTemplate(Request $request)
      {
        $input = $request->all();

        // $patient_id = $input['patient_id'] ?? '';
        // $opd_id = $input['opd_id'] ?? '';
        // $ipd_id = $input['ipd_id'] ?? '';

        $input['standard_charge'] = $input['standard_charge'];
        // $input['unit'] = ! empty($input['unit']) ? $input['unit'] : null;
        $input['subcategory'] = ! empty($input['subcategory']) ? $input['subcategory'] : null;
        $input['method'] = ! empty($input['method']) ? $input['method'] : null;
        $input['report_days'] = ! empty($input['report_days']) ? $input['report_days'] : null;
        $input['patient_id'] = ! empty($input['patient_id']) ? $input['patient_id'] : null;
        // $input['ipd_id'] = ! empty($input['ipd_id']) ? $input['ipd_id'] : null;
        // $input['opd_id'] = ! empty($input['opd_id']) ? $input['opd_id'] : null;

        if (isset($input['parameter_id']) && $input['parameter_id']) {
            foreach ($input['parameter_id'] as $key => $value) {
                if($input['parameter_id'][$key] == null){
                    Flash::error(__('messages.new_change.parameter_name_required'));
                    return redirect()->back();
                }

                // if($input['patient_result'][$key] == null){
                //     Flash::error(__('messages.new_change.patient_result_required'));
                //     return redirect()->back();
                // }
            }
        }

        $this->pathologyTestTemplateRepository->store($input);

        Flash::success(__('messages.pathology_tests').' '.__('messages.common.saved_successfully'));

        // if ($input['create_from_route'] == 'patient') {
        //     return redirect(route('patients.show', $patient_id));
        // } elseif ($input['create_from_route'] == 'opd') {
        //     return redirect(route('opd.patient.show', $opd_id));
        // } else if ($input['create_from_route'] == 'ipd') {
        //     return redirect(route('ipd.patient.show',  $ipd_id));
        // }
        return redirect(route('pathology.test.template.index'));
    }


    public function update(PathologyTestTemplate $pathologyTest, UpdatePathologyTestTemplateRequest $request)
    {
        $input = $request->all();
        $input['standard_charge'] = $input['standard_charge'];
        // $input['unit'] = ! empty($input['unit']) ? $input['unit'] : null;
        $input['subcategory'] = ! empty($input['subcategory']) ? $input['subcategory'] : null;
        $input['method'] = ! empty($input['method']) ? $input['method'] : null;
        $input['report_days'] = ! empty($input['report_days']) ? $input['report_days'] : null;
        $input['patient_id'] = ! empty($input['patient_id']) ? $input['patient_id'] : null;
        // $input['ipd_id'] = ! empty($input['ipd_id']) ? $input['ipd_id'] : null;
        // $input['opd_id'] = ! empty($input['opd_id']) ? $input['opd_id'] : null;
        // $input['status'] = ! empty($input['status']) ? $input['status'] : null;

        if ($input['parameter_id']) {
            foreach ($input['parameter_id'] as $key => $value) {
                if($input['parameter_id'][$key] == null){
                    Flash::error(__('messages.new_change.parameter_name_required'));
                    return redirect()->back();
                }
                // if($input['patient_result'][$key] == null){
                //     Flash::error(__('messages.new_change.patient_result_required'));
                //     return redirect()->back();
                // }
            }
        }

        $this->pathologyTestTemplateRepository->update($input, $pathologyTest);
        Flash::success(__('messages.pathology_tests').' '.__('messages.common.updated_successfully'));

        // if ($input['create_from_route'] == 'patient') {
        //     return redirect(route('patients.show', $input['patient_id']));
        // } elseif ($input['create_from_route'] == 'opd') {
        //     return redirect(route('opd.patient.show', $input['opd_id']));
        // } else if ($input['create_from_route'] == 'ipd') {
        //     return redirect(route('ipd.patient.show',  $input['ipd_id']));
        // }
        return redirect(route('pathology.test.template.index'));
    }

    public function destroy(PathologyTestTemplate $pathologyTest)
    {
        $pathologyTest->parameterItems()->delete();
        $pathologyTest->delete();


        return $this->sendSuccess(__('messages.pathology_tests').' '.__('messages.common.deleted_successfully'));

    }

    public function getStandardCharge($id)
    {
        $standardCharges = Charge::where('charge_category_id', $id)->value('standard_charge');

        return $this->sendResponse($standardCharges, 'StandardCharge retrieved successfully.');
    }

    public function pathologyTestExport()
    {
        return Excel::download(new PathologyTestExport, 'pathology-tests-'.time().'.xlsx');
    }

    public function showModal(PathologyTestTemplate $pathologyTest)
    {
        $pathologyTest->load(['pathologycategory', 'chargecategory']);
        $pathologyParameterItems = PathologyParameterItem::with('pathologyTest','pathologyParameter.pathologyUnit')->wherePathologyId($pathologyTest->id)->get();

        $currency = $pathologyTest->currency_symbol ? strtoupper($pathologyTest->currency_symbol) : strtoupper(getCurrentCurrency());
        $pathologyTest = [
            'test_name' => $pathologyTest->test_name,
            'short_name' => $pathologyTest->short_name,
            'test_type' => $pathologyTest->test_type,
            'pathology_category_name' => $pathologyTest->pathologycategory->name,
            // 'unit' => $pathologyTest->unit,
            'report_days' => $pathologyTest->report_days,
            'standard_charge' => checkValidCurrency($pathologyTest->currency_symbol ?? getCurrentCurrency()) ? moneyFormat($pathologyTest->standard_charge, $currency) : number_format($pathologyTest->standard_charge).''.($pathologyTest->currency_symbol ? getSymbols($pathologyTest->currency_symbol) : getCurrencySymbol()),
            'subcategory' => $pathologyTest->subcategory,
            'method' => $pathologyTest->method,
            'charge_category_name' => $pathologyTest->chargecategory->name,
            'pathologyParameterItems' => $pathologyParameterItems,
            'created_at' => $pathologyTest->created_at,
            'updated_at' => $pathologyTest->updated_at,
        ];

        return $this->sendResponse($pathologyTest, 'Laboratory Test Retrieved Successfully.');
    }

    public function getPathologyParameter($id)
    {
        $data = [];
        $data['parameter'] = PathologyParameter::with('pathologyUnit')->whereId($id)->first();

        return $this->sendResponse($data, 'retrieved');
    }

    public function convertToPDF($id)
    {
        $data = [];
        $data['logo'] = $this->pathologyTestTemplateRepository->getSettingList();
        $data['pathologyTest'] = PathologyTestTemplate::with(['pathologycategory', 'chargecategory'])->where('id',$id)->first();
        $data['pathologyParameterItems'] = PathologyParameterItem::with('pathologyTest','pathologyParameter.pathologyUnit')->wherePathologyId($id)->get();

        // $pdf = PDF::loadView('pathology_tests.pathology_test_pdf', compact('data'));
        // dd($data);

        // return $pdf->stream('Laboratory Test');
        return view('pathology_tests_template.pathology_test_pdf', compact('data'));
    }

    public function show(PathologyTestTemplate $pathologyTest)
    {
        $pathologyParameterItems = PathologyParameterItem::with('pathologyTest','pathologyParameter.pathologyUnit')->wherePathologyId($pathologyTest->id)->get();

        return view('pathology_tests_template.show', compact('pathologyTest','pathologyParameterItems'));
    }

    public function getPathologyTemplateDetails($id)
    {
        $pathologyTemplateDetails = PathologyTestTemplate::whereId($id)->first();

        $response = [
            "report_days" => $pathologyTemplateDetails->report_days,
            "report_date" => date("Y-m-d", strtotime("+" . $pathologyTemplateDetails->report_days . "days")),
            "amount" => $pathologyTemplateDetails->standard_charge,
        ];

        // Add form configuration for dynamic templates
        if ($pathologyTemplateDetails->is_dynamic_form && $pathologyTemplateDetails->form_configuration) {
            $response["form_configuration"] = $pathologyTemplateDetails->form_configuration;
        }

        return $response;
    }
}



