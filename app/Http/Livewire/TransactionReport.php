<?php

namespace App\Http\Livewire;

use App\Models\Account;
use App\Models\ManualBillPayment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use PDF;
use Livewire\WithPagination;

class TransactionReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $formattedStartDate;
    public $formattedEndDate;
    public $dateFilter = 'today';
    public $accountType = 'all';
    public $searchQuery = '';
    public $transactionType = 'all';
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['refresh' => '$refresh', 'print-transaction-report' => 'printReport', 'exportTransactionPDF' => 'exportToPDF'];

    public function mount()
    {
        $this->initializeDates();
    }

    public function initializeDates()
    {
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        $this->updateFormattedDates();
    }

    public function updateFormattedDates()
    {
        $this->formattedStartDate = Carbon::parse($this->startDate)->format('M d, Y');
        $this->formattedEndDate = Carbon::parse($this->endDate)->format('M d, Y');
    }

    public function updatedStartDate($value)
    {
        if (empty($value)) {
            $this->startDate = Carbon::today()->format('Y-m-d');
        }
        $this->updateFormattedDates();
    }

    public function updatedEndDate($value)
    {
        if (empty($value)) {
            $this->endDate = Carbon::today()->format('Y-m-d');
        }
        $this->updateFormattedDates();
    }

    public function changeDateFilter($filter)
    {
        $this->dateFilter = $filter;
        
        switch ($filter) {
            case 'today':
                $this->startDate = Carbon::today()->format('Y-m-d');
                $this->endDate = Carbon::today()->format('Y-m-d');
                // When selecting 'today', also reset other filters to match discharge report behavior
                $this->accountType = 'all';
                $this->transactionType = 'all';
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
                // Keep the current dates
                break;
        }

        $this->updateFormattedDates();
    }

    public function changeAccountType($type)
    {
        $this->accountType = $type;
    }

    public function changeTransactionType($type)
    {
        $this->transactionType = $type;
    }

    public function resetFilters()
    {
        $this->dateFilter = 'today';
        $this->accountType = 'all';
        $this->transactionType = 'all';
        $this->searchQuery = '';
        $this->initializeDates();
    }

    public function formatDate($date)
    {
        return Carbon::parse($date)->format('M d, Y');
    }

    public function getTransactions()
    {
        // Get transactions from both tables
        $transactions = [];
        $totalAmount = 0;
        
        // Regular transactions
        $regularTransactions = Transaction::query()
            ->with('user')
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
            
        if ($this->searchQuery) {
            $regularTransactions->whereHas('user', function($query) {
                $query->where('first_name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('last_name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('email', 'like', '%' . $this->searchQuery . '%');
            })
            ->orWhere('stripe_transaction_id', 'like', '%' . $this->searchQuery . '%');
        }
            
        $regularTransactions = $regularTransactions->get();
        
        // Bill transactions
        $billTransactions = ManualBillPayment::query()
            ->with('bill.patient.patientUser')
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);
            
        if ($this->searchQuery) {
            $billTransactions->whereHas('bill.patient.patientUser', function($query) {
                $query->where('first_name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('last_name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('email', 'like', '%' . $this->searchQuery . '%');
            })
            ->orWhere('transaction_id', 'like', '%' . $this->searchQuery . '%');
        }
            
        if ($this->transactionType !== 'all') {
            if ($this->transactionType === 'manual') {
                $billTransactions->where('is_manual_payment', 1);
            } else {
                $billTransactions->where('is_manual_payment', 0);
            }
        }
            
        $billTransactions = $billTransactions->get();

        // Combine and format transactions
        foreach ($regularTransactions as $transaction) {
            $transactions[] = [
                'id' => $transaction->id,
                'transaction_id' => $transaction->stripe_transaction_id,
                'date' => $transaction->created_at,
                'amount' => $transaction->amount,
                'type' => 'Regular',
                'payment_type' => 'Stripe',
                'status' => $transaction->status,
                'user' => $transaction->user ? [
                    'name' => $transaction->user->full_name ?? 'N/A',
                    'email' => $transaction->user->email ?? 'N/A',
                    'image' => $transaction->user->image_url ?? null,
                ] : null,
                'source' => 'transaction'
            ];
            
            $totalAmount += $transaction->amount;
        }
        
        foreach ($billTransactions as $transaction) {
            $patient = $transaction->bill->patient->patientUser ?? null;
            
            $transactions[] = [
                'id' => $transaction->id,
                'transaction_id' => $transaction->transaction_id,
                'date' => $transaction->created_at,
                'amount' => $transaction->amount,
                'type' => 'Bill Payment',
                'payment_type' => $transaction->is_manual_payment ? 'Manual' : 'Online',
                'status' => $transaction->status == 1 ? 'Approved' : 'Pending',
                'user' => $patient ? [
                    'name' => $patient->full_name ?? 'N/A',
                    'email' => $patient->email ?? 'N/A',
                    'image' => $patient->image_url ?? null,
                ] : null,
                'bill_id' => $transaction->bill_id,
                'source' => 'bill_transaction'
            ];
            
            $totalAmount += $transaction->amount;
        }
        
        // Sort by date (newest first)
        usort($transactions, function($a, $b) {
            return $b['date']->timestamp - $a['date']->timestamp;
        });
        
        // Manual pagination
        $page = $this->page ?? 1;
        $perPage = $this->perPage;
        $total = count($transactions);
        $currentPageItems = array_slice($transactions, ($page - 1) * $perPage, $perPage);
        
        return [
            'transactions' => $currentPageItems,
            'total' => $total,
            'totalAmount' => $totalAmount,
            'currentPage' => $page,
            'perPage' => $perPage,
        ];
    }

    public function printReport()
    {
        $this->emit('print-transaction-report');
    }

    public function exportToCSV()
    {
        $transactionData = $this->getTransactions();
        $transactions = $transactionData['transactions'];
        
        $csv = fopen('php://memory', 'w');
        fputcsv($csv, ['Transaction ID', 'Date', 'Patient', 'Type', 'Payment Method', 'Status', 'Amount']);

        foreach ($transactions as $transaction) {
            fputcsv($csv, [
                $transaction['transaction_id'],
                $transaction['date']->format('M d, Y'),
                $transaction['user']['name'] ?? 'N/A',
                $transaction['type'],
                $transaction['payment_type'],
                $transaction['status'],
                $transaction['amount']
            ]);
        }

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        $this->dispatchBrowserEvent('download-file', [
            'filename' => 'transaction-report-' . now()->format('Y-m-d_H-i-s') . '.csv',
            'data' => base64_encode($content),
            'type' => 'text/csv',
        ]);
    }

    public function exportToExcel()
    {
        $transactionData = $this->getTransactions();
        $transactions = $transactionData['transactions'];
        
        // Create simple Excel-compatible CSV (TSV format works well with Excel)
        $excel = "Transaction ID\tDate\tPatient\tType\tPayment Method\tStatus\tAmount\n";

        foreach ($transactions as $transaction) {
            $excel .= $transaction['transaction_id'] . "\t";
            $excel .= $transaction['date']->format('M d, Y') . "\t";
            $excel .= ($transaction['user']['name'] ?? 'N/A') . "\t";
            $excel .= $transaction['type'] . "\t";
            $excel .= $transaction['payment_type'] . "\t";
            $excel .= $transaction['status'] . "\t";
            $excel .= $transaction['amount'] . "\n";
        }

        $this->dispatchBrowserEvent('download-file', [
            'filename' => 'transaction-report-' . now()->format('Y-m-d_H-i-s') . '.xlsx',
            'data' => base64_encode($excel),
            'type' => 'application/vnd.ms-excel',
        ]);
    }

    #[\Livewire\Attributes\On('exportTransactionPDF')]
    public function exportToPDF()
    {
        try {
            $transactionData = $this->getTransactions();
            $transactions = $transactionData['transactions'];
            $totalAmount = $transactionData['totalAmount'];
            $startDate = Carbon::parse($this->startDate);
            $endDate = Carbon::parse($this->endDate);
            
            $html = view('livewire.transaction-report-pdf', compact('transactions', 'startDate', 'endDate', 'totalAmount'))->render();
            $pdf = PDF::loadHTML($html);
            $output = $pdf->output();

            $this->dispatchBrowserEvent('download-file', [
                'filename' => 'transaction-report-' . now()->format('Y-m-d_H-i-s') . '.pdf',
                'data' => base64_encode($output),
                'type' => 'application/pdf',
            ]);
        } catch (\Throwable $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage());
            $this->dispatchBrowserEvent('export-error', ['message' => 'Could not generate PDF.']);
        }
    }

    public function render()
    {
        $transactionData = $this->getTransactions();
        
        return view('livewire.transaction-report', [
            'transactionData' => $transactionData,
        ]);
    }
}