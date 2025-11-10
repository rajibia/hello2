<?php

namespace App\Http\Livewire;

use App\Models\Patient;
use App\Models\Invoice;
use App\Models\MedicineBill;
use App\Models\IpdPatientDepartment;
use App\Models\PathologyTest;
use App\Models\RadiologyTest;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InvoiceTable extends LivewireTableComponent
{
    use WithPagination;

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = true;

    public $paginationIsEnabled = true;

    public $statusFilter;

    public $buttonComponent = 'invoices.add-button';

    public $FilterComponent = ['invoices.filter-button', Invoice::FILTER_STATUS_ARR];

    protected $model = Patient::class;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];

    public function resetPage($pageName = 'page')
    {
        $rowsPropertyData = $this->getRows()->toArray();
        $prevPageNum = $rowsPropertyData['current_page'] - 1;
        $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
        $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

        $this->setPage($pageNum, $pageName);
    }

    public function changeFilter($param, $value)
    {
        $this->resetPage($this->getComputedPageName());
        $this->statusFilter = $value;
        $this->setBuilder($this->builder());
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('patients.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setThAttributes(function (Column $column) {
            if ($column->isField('total_amount')) {
                return [
                    'class' => 'd-flex justify-content-end',
                    'style' => 'padding-right: 6rem !important',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        if (! getLoggedinPatient()) {
            $this->showButtonOnHeader = true;
            $actionButton = Column::make(__('messages.patient_diagnosis_test.action'),
                'id')->view('invoices.action');
        } else {
            $this->showButtonOnHeader = false;
            $actionButton = Column::make(__('messages.patient_diagnosis_test.action'),
                'id')->view('patient_diagnosis_test.templates.action-button')->hideIf(1);
        }

        return [
            Column::make(__('messages.invoice.patient'), 'id')
                ->view('invoices.columns.patient')
                ->searchable()
                ->sortable(),
            Column::make('Bill Summary', 'id')
                ->view('invoices.columns.patient_bills_summary')
                ->sortable(),
            Column::make(__('messages.invoice.amount'), 'id')
                ->view('invoices.columns.total_amount')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.invoice.invoice_date'), 'id')
                ->view('invoices.columns.last_bill_date')
                ->sortable()
                ->searchable(),
            $actionButton,
        ];
    }

    public function builder(): Builder
    {
        if (! getLoggedinPatient()) {
            // Load all necessary relationships for bill counts
            $query = Patient::whereHas('patientUser')
                ->with([
                    'patientUser.media',
                    'invoices',
                    'medicine_bills',
                    'ipdPatientDepartments',
                    'pathologyTests',
                    'radiologyTests',
                    'maternity'
                ])
                ->select('patients.*');
        } else {
            $patientId = Patient::where('user_id', getLoggedInUserId())->first();
            $query = Patient::whereHas('patientUser')
                ->with([
                    'patientUser.media',
                    'invoices',
                    'medicine_bills',
                    'ipdPatientDepartments',
                    'pathologyTests',
                    'radiologyTests',
                    'maternity'
                ])
                ->select('patients.*')
                ->where('id', $patientId->id);
        }

        return $query;
    }
}
