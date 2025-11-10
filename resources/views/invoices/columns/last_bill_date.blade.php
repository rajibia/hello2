<div class="badge bg-light-info">
    @php
        $latestDate = null;

        // Check invoice dates
        foreach($row->invoices as $invoice) {
            if(!$latestDate || $invoice->invoice_date > $latestDate) {
                $latestDate = $invoice->invoice_date;
            }
        }

        // Check medicine bill dates
        foreach($row->medicine_bills as $bill) {
            if(!$latestDate || $bill->bill_date > $latestDate) {
                $latestDate = $bill->bill_date;
            }
        }

        // Check IPD dates
        foreach($row->ipdPatientDepartments as $ipd) {
            if(!$latestDate || $ipd->created_at > $latestDate) {
                $latestDate = $ipd->created_at;
            }
        }

        // Check pathology test dates
        foreach($row->pathologyTests as $test) {
            if(!$latestDate || $test->created_at > $latestDate) {
                $latestDate = $test->created_at;
            }
        }

        // Check radiology test dates
        foreach($row->radiologyTests as $test) {
            if(!$latestDate || $test->created_at > $latestDate) {
                $latestDate = $test->created_at;
            }
        }

        // Check maternity dates
        foreach($row->maternity as $maternity) {
            if(!$latestDate || $maternity->created_at > $latestDate) {
                $latestDate = $maternity->created_at;
            }
        }
    @endphp

    @if($latestDate)
        {{ \Carbon\Carbon::parse($latestDate)->translatedFormat('jS M, Y') }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
