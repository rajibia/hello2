<?php

namespace App\Http\Livewire;

use App\Models\PathologyTestTemplate;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PathologyTestsTemplateTable extends LivewireTableComponent
{
    public $showButtonOnHeader = true;

    public $buttonComponent = 'pathology_tests_template.add-button';

    protected $model = PathologyTestTemplate::class;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];
    public $patientId;
    public $opdId;
    public $ipdId;
    public function mount($patientId = null, $opdId = null, $ipdId = null)
    {
        $this->patientId = $patientId;

        $this->opdId = $opdId;

        $this->ipdId = $ipdId;
    }
    public function resetPage($pageName = 'page')
    {
        $rowsPropertyData = $this->getRows()->toArray();
        $prevPageNum = $rowsPropertyData['current_page'] - 1;
        $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
        $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

        $this->setPage($pageNum, $pageName);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('pathology_test_templates.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setDefaultSort('created_at', 'desc');
        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center',
                ];
            }
            return [];
        });
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('test_name') || $column->isField('short_name') || $column->isField('test_type') || $column->isField('category_id') || $column->isField('charge_category_id')) {
                return [
                    'class' => 'pt-5',
                ];
            }
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center',
                ];
            }

            return [];
        });

        $this->setConfigurableAreas([
            'toolbar-right-end' => [
                'pathology_tests_template.add-button', [
                    'patientId' => $this->patientId,
                    'ipdId' => $this->ipdId,
                    'opdId' => $this->opdId,
                ],
            ],
        ]);
        //        $this->setThAttributes(function (Column $column) {
        //            return [
        //                'class' => 'w-100',
        //            ];
        //        });
    }
  

    
   

   

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()->hideIf('id'),
            Column::make(__('messages.pathology_test.test_name'), 'test_name')
                ->sortable()->searchable()
                ->view('pathology_tests_template.columns.test_name'),
            Column::make(__('messages.pathology_test.short_name'), 'short_name')
                ->sortable()->searchable(),
            Column::make(__('messages.pathology_test.test_type'), 'test_type')
                ->sortable()->searchable(),
            Column::make(__('messages.pathology_test.category_name'), 'pathologycategory.name')
                ->sortable()->searchable()->view('pathology_tests_template.columns.category_name'),
            // Column::make(__('messages.pathology_test.unit'), 'pathologyunit.name')
                // ->sortable()->searchable()->view('pathology_tests_template.columns.unit'),
            Column::make(__('messages.pathology_test.subcategory'), 'subcategory')
                ->sortable()->searchable(),
            Column::make(__('messages.pathology_test.method'), 'method')
                ->sortable()->searchable(),
            Column::make(__('messages.pathology_test.report_days'), 'report_days')
                ->sortable()->searchable(),
            Column::make(__('messages.pathology_test.charge_category'), 'chargecategory.name')
                ->sortable()->searchable()->view('pathology_tests_template.columns.charge_category'),
            Column::make(__('messages.pathology_test.standard_charge') . " (GHS)", 'standard_charge')
                    ->sortable()->searchable()->view('pathology_tests_template.columns.standard_charge'),
            // Column::make('Status', 'status')
            //     ->view('pathology_tests.columns.status'),
            Column::make(__('messages.common.action'), 'id')->view('pathology_tests_template.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = PathologyTestTemplate::with('pathologycategory', 'chargecategory')->select('pathology_test_templates.*');

        if ($this->patientId != null) {
            $query->where('patient_id', $this->patientId);
        }

        if ($this->opdId != null) {
            $query->where('opd_id', $this->opdId);
        }
        if ($this->ipdId != null) {
            $query->where('ipd_id', $this->ipdId);
        }
        return $query;
    }
}
