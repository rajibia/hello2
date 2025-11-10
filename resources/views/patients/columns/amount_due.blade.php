<div class="d-flex align-items-center mt-2">
    @if(!empty($row->due_amount ))
        {{ checkNumberFormat($row->due_amount , strtoupper(getCurrentCurrency())) }}
    @else
    {{ checkNumberFormat(0 , strtoupper(getCurrentCurrency())) }}
    @endif
</div>
