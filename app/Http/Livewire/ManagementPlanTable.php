<?php

namespace App\Http\Livewire;

use App\Models\ManagementPlan;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ManagementPlanTable extends LivewireTableComponent
{
    protected $model = ManagementPlan::class;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];
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
            ->setDefaultSort('management_plans.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($columnIndex == '3') {
                return [
                    'width' => '8%',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->hideIf(1),
            Column::make(__('messages.advanced_payment.date'), 'created_at')
                ->format(function ($value) {
                    return \Carbon\Carbon::parse($value)->translatedFormat('jS M, Y H:i A');
                })
                ->searchable()
                ->sortable(),
            Column::make('User', 'user_id')
                ->format(function ($value, $row) {
                    return $row->user?->full_name ?? 'N/A';
                })
                ->sortable()
                ->searchable(),
            Column::make('Management Plan', 'management_plan')
                ->format(function ($value) {
                    return nl2br(e($value));
                })
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('management_plans.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = ManagementPlan::with('user')->select('management_plans.*');

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
