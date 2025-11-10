<?php

namespace App\Http\Livewire;

use App\Models\OpdProvisionalDiagnosis;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class OpdProvisionalDiagnosisTable extends LivewireTableComponent
{
    public $showButtonOnHeader = true;

    public $buttonComponent = 'opd_provisional_diagnoses.add-button';

    protected $model = OpdProvisionalDiagnosis::class;

    public $opdDiagnosisId;

    protected $listeners = ['refresh' => '$refresh', 'resetPage'];

    public function resetPage($pageName = 'page')
    {
        $rowsPropertyData = $this->getRows()->toArray();
        $prevPageNum = $rowsPropertyData['current_page'] - 1;
        $prevPageNum = $prevPageNum > 0 ? $prevPageNum : 1;
        $pageNum = count($rowsPropertyData['data']) > 0 ? $rowsPropertyData['current_page'] : $prevPageNum;

        $this->setPage($pageNum, $pageName);
    }

    public function mount(int $opdProvisionalDiagnosisId)
    {
        
        $this->opdDiagnosisId = $opdProvisionalDiagnosisId;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('opd_provisional_diagnoses.created_at', 'desc')
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
            Column::make(__('messages.opd_patient_diagnosis.description'), 'description')
                ->view('opd_provisional_diagnoses.columns.description')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('opd_provisional_diagnoses.columns.action'),
        ];
    }

    public function builder(): Builder
    {
        return OpdProvisionalDiagnosis::whereOpdPatientDepartmentId($this->opdDiagnosisId)->select('opd_provisional_diagnoses.*');
    }
}
