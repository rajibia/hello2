{{-- <a href="{{ route('invoices.create') }}"
   class="btn btn-primary">{{__('messages.invoice.new_invoice')}}</a> --}}
   <div class="me-3">
      <input class="form-control custom-width" id="time_range-revenue" /><b class="caret"></b>
   </div>
   <p style="font-size: 18px;"><strong>Total: </strong>{{ getCurrencySymbol() }} {{ formatCurrency(todayTotalAmount()) }}</p>