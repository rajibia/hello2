<?php

namespace App\Http\Livewire;

use App\Models\IpdPostnatalHistory;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class IpdPostnatalTable extends LivewireTableComponent
{
    protected $model = IpdPostnatalHistory::class;

    public $showButtonOnHeader = true;
    public $buttonComponent = 'ipd_postnatal_history.add-button';
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
            ->setDefaultSort('ipd_postnatal_history.created_at', 'desc')
            ->setQueryStringStatus(false);

        $this->setConfigurableAreas([
            'toolbar-right-end' => [
                'ipd_postnatal_history.add-button', [
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
                ->view('ipd_postnatal_history.columns.date')
                ->searchable()
                ->sortable(),
            Column::make(__('messages.postnatal.labour_time'), 'labour_time')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.postnatal.delivery_time'), 'delivery_time')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.postnatal.routine_question'), 'routine_question')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.postnatal.general_remark'), 'general_remark')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.common.action'), 'id')
                ->view('ipd_postnatal_history.action'),
        ];
    }

    public function builder(): Builder
    {
        $query = IpdPostnatalHistory::query()
            // ->where('is_antenatal', 1) 
            ->select([
                'id',
                'patient_id',
                'labour_time',
                'delivery_time', 
                'routine_question',
                'general_remark',
            ]);


        return $query;
    }
}
