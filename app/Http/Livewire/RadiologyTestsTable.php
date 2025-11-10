<?php

namespace App\Http\Livewire;

use App\Models\RadiologyTest;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RadiologyTestsTable extends LivewireTableComponent
{
    public $showButtonOnHeader = true;

    public $buttonComponent = 'radiology_tests.add-button';

    protected $model = RadiologyTest::class;

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
            ->setDefaultSort('radiology_tests.created_at', 'desc')
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
                'radiology_tests.add-button', [
                    'patientId' => $this->patientId,
                    'ipdId' => $this->ipdId,
                    'opdId' => $this->opdId,
                    'maternityId' => $this->maternityId,
                ],
            ],
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable()->hideIf('id'),
            Column::make(__('messages.radiology_test.bill_no'), 'bill_no')
                ->sortable()->searchable(),
            Column::make(__('messages.radiology_test.patient_name'), 'patient.patientUser.first_name')
                ->view('radiology_tests.columns.patient')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.radiology_test.reference_name'), 'doctor.doctorUser.first_name')
                ->view('radiology_tests.columns.doctor')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.radiology_test.previous_value'), 'previous_report_value')
                ->sortable()->searchable(),
            Column::make(__('messages.radiology_test.discount'), 'discount')
                ->sortable()->searchable(),
            Column::make(__('messages.radiology_test.total_amount'), 'total')
                ->sortable()->searchable()->format(function ($value) {
                    return number_format($value, 2);
                }),
            Column::make(__('messages.radiology_test.amount_paid'), 'amount_paid')
                ->sortable()->searchable()->format(function ($value) {
                    return number_format($value, 2);
                }),
            Column::make(__('messages.radiology_test.balance'), 'balance')
                ->sortable()->searchable()->format(function ($value) {
                    return number_format($value, 2);
                }),
            Column::make('Status', 'status')
                ->view('radiology_tests.columns.status'),
            Column::make(__('messages.common.action'), 'id')->view('radiology_tests.action'),
        ];
    }

    public function builder(): Builder
    {

        $query = RadiologyTest::whereHas('patient.patientUser')->whereHas('doctor')
                ->with(['patient.patientUser', 'radiologycategory', 'chargecategory', 'doctor.doctorUser'])->select('radiology_tests.*');

        if ($this->patientId != null) {
            $query->where('patient_id', $this->patientId);
        }

        if ($this->opdId != null) {
            $query->where('opd_id', $this->opdId);
        }
        if ($this->ipdId != null) {
            $query->where('ipd_id', $this->ipdId);
        }
        if ($this->maternityId != null) {
            $query->where('maternity_id', $this->maternityId);
        }
        return $query;
    }
}
