<?php

namespace App\Http\Livewire;

use App\Models\IpdBill;
use App\Models\IpdPatientDepartment;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class PatientIpdBillTable extends DataTableComponent
{
    public $showFilterOnHeader = false;
    
    public $showButtonOnHeader = false;
    
    public int $patientId;
    
    protected $model = IpdBill::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setPerPageAccepted([10, 25, 50, 100]);
        $this->setPerPage(10);
        $this->setTableRowUrl(function ($row) {
            return route('ipd.patient.show', $row->ipdPatient->id);
        });
    }

    public function mount(int $patientId): void
    {
        $this->patientId = $patientId;
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.bill.bill_id'), 'ipdPatient.ipd_number')
                ->view('patients.patient_bill_column.ipd_bill_id')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.bill.bill_date'), 'created_at')
                ->view('patients.patient_bill_column.ipd_bill_date')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.bill.amount'), 'net_payable_amount')
                ->view('patients.patient_bill_column.ipd_amount')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('patients.patient_bill_column.ipd_action'),
        ];
    }

    public function builder(): Builder
    {
        return IpdBill::with('ipdPatient')
            ->whereHas('ipdPatient', function ($query) {
                $query->where('patient_id', $this->patientId);
            })
            ->select('ipd_bills.*');
    }
}