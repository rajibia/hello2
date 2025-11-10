@modulePermission('payment-reports', 'view')
    <a data-turbo="false" href="{{ route('payment.report.excel') }}" class="btn btn-primary">
        {{__('messages.common.export_to_excel')}}
    </a>
@endmodulePermission
