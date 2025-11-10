<?php

namespace App\Http\Livewire;

use App\Models\Antenatal;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AntenatalTable extends LivewireTableComponent
{
    protected $model = Antenatal::class;

    public $showButtonOnHeader = true;
    public $buttonComponent = 'maternity.antenatal-add-button';
    public $showFilterOnHeader = false;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];
    public $patientId;
    public $opdId;

    public function mount($ipdId = null)
    {
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
        ->setDefaultSort('antenatals.created_at', 'desc')
        ->setQueryStringStatus(false);

    $this->setConfigurableAreas([
        'toolbar-right-end' => [
            'maternity.antenatal-add-button', [
                'ipdId' => $this->ipdId,
            ],
        ],
    ]);
}

    public function columns(): array
    {
        return [

            // Column::make(__('messages.antenatal.patient_id'), 'patient_id')
            //     ->sortable()
            //     ->searchable(),
            Column::make(__('messages.antenatal.date'), 'created_at')
                ->view('antenatal.columns.date')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.antenatal.condition'), 'condition')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.antenatal.condition'), 'patient_id')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.antenatal.antenatal_weight'), 'antenatal_weight')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.antenatal.urine_sugar'), 'urine_sugar')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.antenatal.haemorrhoids'), 'haemorrhoids')
                ->view('antenatal.columns.haemorrhoids')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.antenatal.bleeding'), 'bleeding')
                ->view('antenatal.columns.bleeding')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.antenatal.headache'), 'headache')
                ->view('antenatal.columns.headache')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.antenatal.presentation_position'), 'presentation_position')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('antenatal.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = Antenatal::query()
            ->select([
                'id',
                'patient_id',
                'condition',
                'urine_sugar',
                'bleeding',
                'headache',
                'presentation_position',
                'antenatal_weight',
                'haemorrhoids',
                'created_at',
            ]);

        if ($this->opdId) {
            $query->where('patient_id', $this->opdId); // Filter by patient_id for maternity
        }

        return $query;
    }

}
