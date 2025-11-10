<script id="invoiceItemTemplate" type="text/x-jsrender">
    <tr class="border-bottom">
        <td class="text-center pt-6 item-number">1</td>
        <td class="table__item-desc">
            <select class="form-select chargeId added-row-select" name="charge_id[]" placeholder="<?php echo __('messages.common.choose') . ' ' . __('messages.charges') ?>" data-control="select2" data-dropdown-parent="#createInvoiceModal" required>
                <option selected="selected" value=""><?php echo __('messages.common.choose') . ' ' . __('messages.charges') ?></option>
                {{props charges}}
                    <option value="{{:prop.key}}">{{:prop.value}}</option>
                {{/props}}
            </select>
        </td>
        <td class="text-center">
            <input class="form-control" name="description[]" type="text" placeholder="<?php echo __('messages.invoice.description') ?>">
        </td>
        <td class="table__qty text-center">
            <input class="form-control qty" required name="quantity[]" type="number" min="1" value="1" placeholder="<?php echo __('messages.invoice.qty') ?>">
        </td>
        <td class="text-center">
            <input class="form-control price-input price" required name="price[]" type="text" placeholder="<?php echo __('messages.invoice.price') ?>">
        </td>
        <td class="amount text-center item-total pt-5 ms-2 text-nowrap">
            GH₵ <span class="amount">0.00</span>
        </td>
        <td class="text-end">
            <a href="javascript:void(0)" title="<?php echo __('messages.common.delete') ?>"
               class="deleteInvoiceItem btn px-2 text-danger fs-3">
                <i class="fa-solid fa-trash"></i>
            </a>
        </td>
    </tr>
</script>