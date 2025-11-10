<?php

namespace App\Http\Livewire;

use App\Models\Invoice;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TodayPaymentReportsTable extends LivewireTableComponent
{
    use WithPagination;

    public $showButtonOnHeader = true;

    public $showFilterOnHeader = false;

    public $paginationIsEnabled = true;

    public $buttonComponent = 'today_payment_reports.add-button';

    public $FilterComponent = ['today_payment_reports.filter-button', Invoice::FILTER_STATUS_ARR];

    protected $model = Invoice::class;

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
            ->setDefaultSort('invoices.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setThAttributes(function (Column $column) {
            if ($column->isField('amount')) {
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
                'id')->view('today_payment_reports.action');
        } else {
            $this->showButtonOnHeader = false;
            $actionButton = Column::make(__('messages.patient_diagnosis_test.action'),
                'id')->view('patient_diagnosis_test.templates.action-button')->hideIf(1);
        }

        return [
            Column::make(__('messages.invoice.invoice_id'), 'invoice_id')
                ->view('today_payment_reports.columns.invoice_id')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.invoice.patient'), 'patient.patientUser.first_name')
                ->view('today_payment_reports.columns.patient')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.invoice.invoice_date'), 'invoice_date')
                ->view('today_payment_reports.columns.invoice_date')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.invoice.amount'), 'amount')
                ->view('today_payment_reports.columns.amount')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.items'), 'discount')
                ->view('today_payment_reports.columns.items')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.status'), 'status')
                ->view('today_payment_reports.columns.status'),
        ];
    }

    public function builder(): Builder
    {
        if (! getLoggedinPatient()) {
            $query = Invoice::whereHas('patient.patientUser')->whereHas('invoiceItems')->with(['patient.patientUser', 'invoiceItems'])->select('invoices.*');
        } else {
            $patientId = Patient::where('user_id', getLoggedInUserId())->first();
            $query = Invoice::whereHas('patient.patientUser')->whereHas('invoiceItems')->with(['patient.patientUser', 'invoiceItems'])->select('invoices.*')->where('patient_id',
                $patientId->id);
        }

        $query->when(isset($this->statusFilter), function (Builder $q) {
            if ($this->statusFilter == 0) {
                $q->where('invoices.status', $this->statusFilter);
            }
            if ($this->statusFilter == 1) {
                $q->where('invoices.status', $this->statusFilter);
            }
        });

        $query->where('invoices.status', 0)
        ->whereDate('invoice_date', Carbon::today());

        return $query;
    }
}
