<?php

namespace App\Http\Livewire;

use App\Models\IpdProvisionalDiagnosis;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class IpdProvisionalDiagnosisTable extends LivewireTableComponent
{
    public $showButtonOnHeader = true;

    public $buttonComponent = 'ipd_provisional_diagnoses.add-button';

    protected $model = IpdProvisionalDiagnosis::class;

    public $ipdDiagnosisId;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

    public function resetPage($pageName = 'page')
    {
        $rowsPropertyData = $this->getRows()->toArray();
        $prevPageNum = $rowsPropertyData['current_page'] - 1;
        $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
        $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

        $this->setPage($pageNum, $pageName);
    }

    public function mount(int $ipdProvisionalDiagnosisId)
    {
        
        $this->ipdDiagnosisId = $ipdProvisionalDiagnosisId;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('ipd_provisional_diagnoses.created_at', 'desc')
            ->setQueryStringStatus(false);
        $this->setThAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'd-flex w-75 text-center',
                    'style' => 'width: 85% !important',
                ];
            }

            return [

            ];
        });
        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('id') || $column->isField('description')) {
                return [
                    'class' => 'pt-5',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        
        return [
            Column::make(__('messages.ipd_patient_diagnosis.description'), 'description')
                ->view('ipd_provisional_diagnoses.columns.description')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('ipd_provisional_diagnoses.columns.action'),
        ];
    }

    public function builder(): Builder
    {
        return IpdProvisionalDiagnosis::whereIpdPatientDepartmentId($this->ipdDiagnosisId)->select('ipd_provisional_diagnoses.*');
    }
}
