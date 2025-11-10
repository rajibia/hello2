<?php

namespace App\Http\Livewire;

use App\Models\PostnatalHistory;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PostnatalTable extends LivewireTableComponent
{
    protected $model = PostnatalHistory::class;

    public $showButtonOnHeader = true;
    public $buttonComponent = 'maternity.postnatal-add-button';
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
            ->setDefaultSort('postnatal_history.created_at', 'desc')
            ->setQueryStringStatus(false);

        $this->setConfigurableAreas([
            'toolbar-right-end' => [
                'maternity.postnatal-add-button', [
                    'ipdId' => $this->ipdId,
                ],
            ],
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('DATE', 'created_at')
                ->view('postnatal.columns.date')
                ->searchable()
                ->sortable(),
            Column::make('LABOUR TIME', 'labour_time')
                ->sortable()
                ->searchable(),
            Column::make('DELIVERY TIME', 'delivery_time')
                ->sortable()
                ->searchable(),
            Column::make('ROUTINE QUESTION', 'routine_question')
                ->sortable()
                ->searchable(),
            Column::make('GENERAL REMARK', 'general_remark')
                ->sortable()
                ->searchable(),
            Column::make('ACTIONS', 'id')
                ->view('postnatal.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = PostnatalHistory::query()
            ->select([
                'id',
                'patient_id',
                'labour_time',
                'delivery_time',
                'routine_question',
                'general_remark',
                'created_at',
            ]);

        if ($this->ipdId) {
            $query->where('patient_id', $this->ipdId); // Filter by patient_id for maternity
        }

        return $query;
    }
}
