<script id="radiologyTestBill" type="text/x-jsrender">
    {{if !isEdit}}

        <tr>
            <td class="table__item-desc">
                <select class="form-select radiology-parameter-data select2Selector radiology_test_name" name="test_name[]" data-id="{{:uniqueId}}" required>
                <option value="" disabled selected> <?php echo __('messages.radiology_test.select_test_name'); ?></option>
                {{for parameters}}
                        <option value="{{:key}}">{{:value}}</option>
                    {{/for}}
                </select>
            </td>
            <td>
                <input class="form-control" name="report_days[]" type="text" readonly id="report_days" placeholder="<?php echo  __('messages.radiology_test.report_days'); ?>">
            </td>
            <td>
                <input class="form-control" required="" value='' name="report_date[]" id="report_date" type="date"  placeholder="<?php echo  __('messages.radiology_test.report_date'); ?>">
            </td>
            <td>
                <input class="form-control amount_summand" required="" value='' name="amount[]" id="amount" type="text" readonly placeholder="<?php echo  __('messages.radiology_test.amount'); ?>">
            </td>
            <td class="text-center">
                <a href="javascript:void(0)" title="{{__('messages.common.delete')}}"
                class="delete-radiology-parameter-test  btn px-1 text-danger fs-3 pe-0">
                        <i class="fa-solid fa-trash"></i>
                </a>
            </td>
        </tr>

    {{else}}


        <tr>
            <td class="table__item-desc">
                <select class="form-select select2Selector edit_rad"  name="test_name[]" data-id="{{:uniqueId}}" required>
                <option value="" disabled selected> <?php echo __('messages.radiology_test.select_test_name') ?></option>
                {{for parameters}}
                        <option value="{{:key}}">{{:value}}</option>
                    {{/for}}
                </select>
            </td>
            <td>
                <input class="form-control" name="report_days[]" type="text" readonly id="report_ed_rad_days" placeholder="<?php echo  __('messages.radiology_test.report_days'); ?>">
            </td>
            <td>
                <input class="form-control" required="" value='' name="report_date[]" id="report_ed_rad_date" type="date"  placeholder="<?php echo  __('messages.radiology_test.report_date'); ?>">
            </td>
            <td>
                <input class="form-control amount_ed_rad_summand" required="" value='' name="amount[]" id="amount_ed_rad" type="text" readonly placeholder="<?php echo  __('messages.radiology_test.amount'); ?>">
            </td>
            <td class="text-center">
                <a href="javascript:void(0)" title="{{__('messages.common.delete')}}"
                class="delete-radiology-parameter-test-ed  btn px-1 text-danger fs-3 pe-0">
                        <i class="fa-solid fa-trash"></i>
                </a>
            </td>
        </tr>


    {{/if}}

</script>
