<?php

namespace App\Http\Livewire;

use App\Models\PreviousObstetricHistory;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MaternityObstetricHistoryTable extends LivewireTableComponent
{
    protected $model = PreviousObstetricHistory::class;

    public $showButtonOnHeader = true;
    public $buttonComponent = 'maternity.obstetric-add-button';
    public $showFilterOnHeader = false;

    protected $listeners = ['refresh' => '$refresh', 'changeFilter', 'resetPage'];
    public $patientId;
    public $ipdId;

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
            ->setDefaultSort('previous_obstetric_history.created_at', 'desc')
            ->setQueryStringStatus(false);

        $this->setConfigurableAreas([
            'toolbar-right-end' => [
                'maternity.obstetric-add-button', [
                    'ipdId' => $this->ipdId,
                ],
            ],
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('DATE', 'created_at')
                ->view('maternity.obstetric.columns.date')
                ->searchable()
                ->sortable(),
            Column::make('PLACE OF DELIVERY', 'place_of_delivery')
                ->sortable()
                ->searchable(),
            Column::make('DURATION OF PREGNANCY', 'duration_of_pregnancy')
                ->sortable()
                ->searchable(),
            Column::make('COMPLICATIONS IN PREGNANCY OR PUERPERIUM', 'complication_in_pregnancy_or_puerperium')
                ->sortable()
                ->searchable(),
            Column::make('BIRTH WEIGHT', 'birth_weight')
                ->sortable()
                ->searchable(),
            Column::make('GENDER', 'gender')
                ->sortable()
                ->searchable(),
            Column::make('INFANT FEEDING', 'infant_feeding')
                ->sortable()
                ->searchable(),
            Column::make('BIRTH STATUS', 'birth_status')
                ->sortable()
                ->searchable(),
            Column::make('ACTIONS', 'id')
                ->view('maternity.obstetric.action'),
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
                'created_at',
            ]);

        if ($this->ipdId) {
            $query->where('patient_id', $this->ipdId);
        }

        return $query;
    }
}
