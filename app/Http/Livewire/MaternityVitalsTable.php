<?php

namespace App\Http\Livewire;

use App\Models\Vital;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MaternityVitalsTable extends LivewireTableComponent
{
    protected $model = Vital::class;

    public $showButtonOnHeader = true;
    public $buttonComponent = 'maternity.vitals-add-button';
    public $showFilterOnHeader = false;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];
    public $patientId;
    public $ipdId;
    public $maternityId;

    public function mount($ipdId = null, $maternityId = null)
    {
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
            ->setDefaultSort('vitals.created_at', 'desc')
            ->setQueryStringStatus(false);

        $this->setConfigurableAreas([
            'toolbar-right-end' => [
                'maternity.vitals-add-button', [
                    'ipdId' => $this->ipdId,
                    'maternityId' => $this->maternityId,
                ],
            ],
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('DATE', 'created_at')
                ->view('maternity.vitals.columns.date')
                ->searchable()
                ->sortable(),
            Column::make('BLOOD PRESSURE', 'bp')
                ->sortable()
                ->searchable(),
            Column::make('PULSE', 'pulse')
                ->sortable()
                ->searchable(),
            Column::make('RESPIRATION', 'respiration')
                ->sortable()
                ->searchable(),
            Column::make('TEMPERATURE', 'temperature')
                ->sortable()
                ->searchable(),
            Column::make('OXYGEN SATURATION', 'oxygen_saturation')
                ->sortable()
                ->searchable(),
            Column::make('HEIGHT', 'height')
                ->sortable()
                ->searchable(),
            Column::make('WEIGHT', 'weight')
                ->sortable()
                ->searchable(),
            Column::make('BMI')
                ->label(function ($row) {
                    $bmi = $row->bmi;
                    if (!$bmi) return 'N/A';
                    if ($bmi < 18.5) {
                        return "<span class='text-blue-500 font-bold'> (Underweight)</span>";
                    } elseif ($bmi < 25) {
                        return "<span class='text-green-600 font-bold'> (Normal)</span>";
                    } elseif ($bmi < 30) {
                        return "<span class='text-yellow-500 font-bold'> (Overweight)</span>";
                    } else {
                        return "<span class='text-red-600 font-bold'> (Obese)</span>";
                    }
                })
                ->html(),
            Column::make('ACTIONS', 'id')
                ->view('maternity.vitals.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = Vital::whereHas('patient.patientUser')->with('patient.patientUser')->select('vitals.*');

        if ($this->ipdId) {
            $query->where('patient_id', $this->ipdId);
        }

        return $query;
    }
}
