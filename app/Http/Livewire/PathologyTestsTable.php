<?php

namespace App\Http\Livewire;

use App\Models\PathologyTest;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PathologyTestsTable extends LivewireTableComponent
{
    public $showButtonOnHeader = true;

    public $buttonComponent = 'pathology_tests.add-button';

    protected $model = PathologyTest::class;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];
    public $patientId;
    public $opdId;
    public $ipdId;
    public $maternityId;

    public function mount($patientId = null, $opdId = null, $ipdId = null, $maternityId = null)
    {
        $this->patientId = $patientId;
        $this->opdId = $opdId;
        $this->ipdId = $ipdId;
        $this->maternityId = $maternityId;
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
            ->setDefaultSort('pathology_tests.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setDefaultSort('created_at', 'desc');

        // Set high per page limit to show all tests
        $this->setPerPageAccepted([10, 25, 50, 100, -1]); // Add -1 to accepted values
        $this->setPerPage(-1); // Show all results
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
                'pathology_tests.add-button', [
                    'patientId' => $this->patientId,
                    'ipdId' => $this->ipdId,
                    'opdId' => $this->opdId,
                    'maternityId' => $this->maternityId,
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
            Column::make(__('messages.pathology_test.bill_no'), 'bill_no')
                ->sortable()->searchable()
                ->view('pathology_tests.columns.bill_no'),
            // Column::make(__('messages.pathology_test.test_name'), 'pathologytesttemplate.test_name')
            //     ->sortable()->searchable()
            //     ->view('pathology_tests.columns.test_name'),
            // Column::make(__('messages.pathology_test.report_date'), 'report_date')
            //     ->sortable()->searchable(),
            Column::make(__('messages.pathology_test.patient_name'), 'patient.patientUser.first_name')
                ->view('pathology_tests.columns.patient')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.pathology_test.reference_name'), 'doctor.doctorUser.first_name')
                ->view('pathology_tests.columns.doctor')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.pathology_test.discount'), 'discount')
                ->sortable()->searchable(),
            Column::make(__('messages.pathology_test.total_amount'), 'total')
                ->sortable()->searchable()->format(function ($value) {
                    return number_format($value, 2);
                }),
            Column::make(__('messages.pathology_test.amount_paid'), 'amount_paid')
                ->sortable()->searchable()->format(function ($value) {
                    return number_format($value, 2);
                }),
            Column::make(__('messages.pathology_test.balance'), 'balance')
                ->sortable()->searchable()->format(function ($value) {
                    return number_format($value, 2);
                }),
            Column::make('Payment Status', 'balance')
                ->view('pathology_tests.columns.payment_status'),
            // Column::make(__('messages.pathology_test.test_type'), 'test_type')
            //     ->sortable()->searchable(),
            // Column::make(__('messages.pathology_test.category_name'), 'pathologycategory.name')
            //     ->sortable()->searchable()->view('pathology_tests.columns.category_name'),
            // Column::make(__('messages.pathology_test.charge_category'), 'chargecategory.name')
            //     ->sortable()->searchable()->view('pathology_tests.columns.charge_category'),
            Column::make('Status', 'status')
                ->view('pathology_tests.columns.status'),
            Column::make(__('messages.common.action'), 'id')->view('pathology_tests.action'),
        ];
    }

    public function builder(): Builder
    {

        $query = PathologyTest::whereHas('patient.patientUser')->whereHas('doctor')
                ->with(['patient.patientUser', 'pathologycategory', 'chargecategory', 'doctor.doctorUser'])->select('pathology_tests.*');



        // $query = PathologyTest::with('pathologycategory', 'chargecategory')->select('pathology_tests.*');

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

   
    public function gotoPage($page, $paginatorAlias = 'page')
{
    $this->setPage($page, $paginatorAlias);
}
}
