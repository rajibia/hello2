<script id="pathologyTestBill" type="text/x-jsrender">
    {{if !isEdit}}

        <tr>
            <td class="table__item-desc">
                <select class="form-select  select2Selector add_path"  name="test_name[]" data-id="{{:uniqueId}}" required>
                <option value="" disabled selected> __('messages.pathology_test.test_name')</option>
                {{for parameters}}
                        <option value="{{:key}}">{{:value}}</option>
                    {{/for}}
                </select>
            </td>
            <td>
                <input class="form-control" name="report_days[]" type="text" readonly id="report_days" placeholder="<?php echo  __('messages.pathology_test.report_days'); ?>">
            </td>
            <td>
                <input class="form-control" required="" value='' name="report_date[]" id="report_date" type="date"  placeholder="<?php echo  __('messages.pathology_test.report_date'); ?>">
            </td>
            <td>
                <input class="form-control amount_summand" required="" value='' name="amount[]" id="amount" type="text" readonly placeholder="<?php echo  __('messages.pathology_test.amount'); ?>">
            </td>
            <td class="text-center">
                <a href="javascript:void(0)" title="{{__('messages.common.delete')}}"
                class="delete-parameter-test-add-path  btn px-1 text-danger fs-3 pe-0">
                        <i class="fa-solid fa-trash"></i>
                </a>
            </td>
        </tr>

    {{else}}


        <tr>
            <td class="table__item-desc">
                <select class="form-select select2Selector edit_path"  name="test_name[]" data-id="{{:uniqueId}}" required>
                <option value="" disabled selected> __('messages.pathology_test.test_name')</option>
                {{for parameters}}
                        <option value="{{:key}}">{{:value}}</option>
                    {{/for}}
                </select>
            </td>
            <td>
                <input class="form-control" name="report_days[]" type="text" readonly id="report_ed_days" placeholder="<?php echo  __('messages.pathology_test.report_days'); ?>">
            </td>
            <td>
                <input class="form-control" required="" value='' name="report_date[]" id="report_ed_date" type="date"  placeholder="<?php echo  __('messages.pathology_test.report_date'); ?>">
            </td>
            <td>
                <input class="form-control amount_ed_summand" required="" value='' name="amount[]" id="amount_ed" type="text" readonly placeholder="<?php echo  __('messages.pathology_test.amount'); ?>">
            </td>
            <td class="text-center">
                <a href="javascript:void(0)" title="{{__('messages.common.delete')}}"
                class="delete-parameter-test-edit-path  btn px-1 text-danger fs-3 pe-0">
                        <i class="fa-solid fa-trash"></i>
                </a>
            </td>
        </tr>


    {{/if}}

</script>
