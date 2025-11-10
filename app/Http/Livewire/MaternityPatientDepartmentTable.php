<?php

namespace App\Http\Livewire;

use App\Models\Maternity;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MaternityPatientDepartmentTable extends LivewireTableComponent
{
    public $showButtonOnHeader = false;

    public $showFilterOnHeader = false;

    public $paginationIsEnabled = true;

    protected $model = Maternity::class;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

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
            ->setDefaultSort('maternity.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setThAttributes(function (Column $column) {
            if ($column->isField('standard_charge')) {
                return [
                    'class' => 'text-end',
                    'style' => 'padding-right: 7rem !important',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.maternity_patient.maternity_number'), 'id')
                ->hideIf('id')
                ->sortable(),
            Column::make(__('messages.maternity_patient.maternity_number'), 'maternity_number')
                ->view('maternity_patient_list.templates.column.maternity_no')
                ->sortable()->searchable(),
            Column::make(__('messages.ipd_patient.doctor_id'), 'doctor.doctorUser.first_name')
                ->view('maternity_patient_list.templates.column.doctor')
                ->sortable()->searchable(),
            Column::make(__('messages.maternity_patient.appointment_date'), 'appointment_date')
                ->view('maternity_patient_list.templates.column.appointment_date')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.doctor_maternity_charge.standard_charge'), 'standard_charge')
                ->view('maternity_patient_list.templates.column.standard_charge')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.ipd_payments.payment_mode'), 'payment_mode')
                ->view('maternity_patient_list.templates.column.payment_mode')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.user.phone'), 'patient.patientUser.phone')
                ->view('maternity_patient_list.templates.column.phone')
                ->sortable()->searchable(),
            Column::make(__('messages.maternity_patient.total_visits'), 'created_at')
                ->view('maternity_patient_list.templates.column.total_visits')
                ->sortable(),
        ];
    }

    public function builder(): Builder
    {
        /** @var Maternity $query */
        $query = Maternity::with([
            'patient.patientUser', 'doctor.doctorUser',
        ])->where('patient_id', getLoggedInUser()->owner_id)->select('maternity.*');

        return $query;
    }
}
