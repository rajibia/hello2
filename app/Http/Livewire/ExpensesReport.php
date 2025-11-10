<?php

namespace App\Http\Livewire;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ExpensesReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $dateFilter = 'today';
    public $formattedStartDate;
    public $formattedEndDate;
    public $searchQuery = '';
    public $expenseHeadFilter = 'all';
    public $perPage = 10;
    public $expenseHeads = [];

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount()
    {
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        $this->updateFormattedDates();
        $this->expenseHeads = Expense::FILTER_EXPENSE_HEAD;
    }

    public function changeDateFilter($filter)
    {
        $this->dateFilter = $filter;
        
        switch ($filter) {
            case 'today':
                $this->startDate = Carbon::today()->format('Y-m-d');
                $this->endDate = Carbon::today()->format('Y-m-d');
                // When selecting 'today', also reset other filters to match other reports behavior
                $this->expenseHeadFilter = 'all';
                $this->searchQuery = '';
                break;
            case 'yesterday':
                $this->startDate = Carbon::yesterday()->format('Y-m-d');
                $this->endDate = Carbon::yesterday()->format('Y-m-d');
                break;
            case 'this_week':
                $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'custom':
                // Keep the existing dates for custom filter
                break;
        }
        
        $this->updateFormattedDates();
    }

    public function updatedStartDate()
    {
        // If end date is before start date, update end date to match start date
        if (Carbon::parse($this->endDate)->lt(Carbon::parse($this->startDate))) {
            $this->endDate = $this->startDate;
        }
        $this->dateFilter = 'custom';
        $this->updateFormattedDates();
    }

    public function updatedEndDate()
    {
        // If end date is before start date, update start date to match end date
        if (Carbon::parse($this->endDate)->lt(Carbon::parse($this->startDate))) {
            $this->startDate = $this->endDate;
        }
        $this->dateFilter = 'custom';
        $this->updateFormattedDates();
    }

    public function updateFormattedDates()
    {
        $this->formattedStartDate = Carbon::parse($this->startDate)->format('M d, Y');
        $this->formattedEndDate = Carbon::parse($this->endDate)->format('M d, Y');
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->searchQuery = '';
        $this->expenseHeadFilter = 'all';
        $this->dateFilter = 'today';
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        $this->updateFormattedDates();
    }

    public function getExpenses()
    {
        // Start with expenses query
        $query = Expense::query()
            ->whereBetween('date', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
        
        // Apply expense head filter
        if ($this->expenseHeadFilter !== 'all') {
            $query->where('expense_head', $this->expenseHeadFilter);
        }
        
        // Apply search filter
        if (!empty($this->searchQuery)) {
            $query->where(function (Builder $q) {
                $q->where('name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('invoice_number', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('description', 'like', '%' . $this->searchQuery . '%');
            });
        }
        
        // Get all expense records first to calculate total
        $allExpenses = $query->get();
        
        // Calculate total amount
        $totalAmount = $allExpenses->sum('amount');
        
        // Get paginated records
        $expenses = $query->orderBy('date', 'desc')->paginate($this->perPage);
        
        // Process records for display
        $expenseRecords = [];
        $expenseHeads = Expense::EXPENSE_HEAD;
        
        foreach ($expenses as $expense) {
            // Add to records array
            $expenseRecords[] = [
                'id' => $expense->id,
                'expense_head' => $expenseHeads[$expense->expense_head] ?? 'Unknown',
                'expense_head_id' => $expense->expense_head,
                'name' => $expense->name,
                'invoice_number' => $expense->invoice_number,
                'date' => Carbon::parse($expense->date)->format('M d, Y'),
                'amount' => $expense->amount,
                'description' => $expense->description,
                'document_url' => $expense->document_url,
            ];
        }
        
        return [
            'data' => $expenseRecords,
            'total' => $expenses->total(),
            'per_page' => $this->perPage,
            'current_page' => $expenses->currentPage(),
            'last_page' => $expenses->lastPage(),
            'total_amount' => $totalAmount,
            'paginator' => $expenses,
        ];
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        $expenses = $this->getExpenses();
        
        return view('livewire.expenses-report', [
            'expenses' => $expenses,
        ]);
    }
}
