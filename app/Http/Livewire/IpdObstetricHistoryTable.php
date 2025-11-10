<?php

namespace App\Http\Livewire;

use App\Models\PreviousObstetricHistory;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class IpdObstetricHistoryTable extends LivewireTableComponent
{
    protected $model = PreviousObstetricHistory::class;

    public $showButtonOnHeader = true;
    public $buttonComponent = 'ipd_obstetric_history.add-button';
    public $showFilterOnHeader = false;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];
    public $patientId;
    public $ipdId;

    public function mount($ipdId = null, $patientId = null)
    {
        $this->ipdId = $ipdId;
        $this->patientId = $patientId;
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
            ->setDefaultSort('previous_obstetric_history.created_at', 'desc')
            ->setQueryStringStatus(false);

        $this->setConfigurableAreas([
            'toolbar-right-end' => [
                'ipd_obstetric_history.add-button', [
                    'ipdId' => $this->ipdId,
                ],
            ],
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.antenatal.date'), 'created_at')
                ->view('ipd_obstetric_history.columns.date')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.previous_obstetric_history.place_of_delivery'), 'place_of_delivery')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.previous_obstetric_history.duration_of_pregnancy'), 'duration_of_pregnancy')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.previous_obstetric_history.complications'), 'complication_in_pregnancy_or_puerperium')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.previous_obstetric_history.birth_weight'), 'birth_weight')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.previous_obstetric_history.gender'), 'gender')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.previous_obstetric_history.infant_feeding'), 'infant_feeding')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.previous_obstetric_history.birth_status'), 'birth_status')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.previous_obstetric_history.alive'), 'alive')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.previous_obstetric_history.alive_or_dead_date'), 'alive_or_dead_date')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.previous_obstetric_history.previous_medical_history'), 'previous_medical_history')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.previous_obstetric_history.special_instruction'), 'special_instruction')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('ipd_obstetric_history.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = PreviousObstetricHistory::query()
            ->select([
                'id',
                'patient_id',
                'place_of_delivery',
                'duration_of_pregnancy',
                'complication_in_pregnancy_or_puerperium',
                'birth_weight',
                'gender',
                'infant_feeding',
                'birth_status',
                'alive',
                'alive_or_dead_date',
                'previous_medical_history',
                'special_instruction',
            ]);

        return $query;
    }
}
