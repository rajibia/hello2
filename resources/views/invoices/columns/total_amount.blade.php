<div class="d-flex justify-content-end pe-22">
    @php
        $totalAmount = 0;

        // Add invoice amounts
        foreach($row->invoices as $invoice) {
            $totalAmount += $invoice->amount - ($invoice->discount ? ($invoice->amount * $invoice->discount / 100) : 0);
        }

        // Add medicine bill amounts
        foreach($row->medicine_bills as $bill) {
            $totalAmount += $bill->total - ($bill->discount ? ($bill->total * $bill->discount / 100) : 0);
        }

        // Add IPD amounts
        foreach($row->ipdPatientDepartments as $ipd) {
            if($ipd->bill && $ipd->bill->total_charges) {
                $totalAmount += $ipd->bill->total_charges;
            }
        }

        // Add pathology test amounts
        foreach($row->pathologyTests as $test) {
            $totalAmount += $test->balance ?? 0;
        }

        // Add radiology test amounts
        foreach($row->radiologyTests as $test) {
            $totalAmount += $test->balance ?? 0;
        }

        // Add maternity amounts
        foreach($row->maternity as $maternity) {
            if($maternity->bill && $maternity->bill->total_charges) {
                $totalAmount += $maternity->bill->total_charges;
            }
        }
    @endphp

    @if($totalAmount > 0)
        {{ checkNumberFormat($totalAmount, strtoupper(getCurrentCurrency())) }}
    @else
        {{ __('messages.common.n/a') }}
    @endif
</div>
