@extends('layouts.app')
@section('title')
    {{ __('messages.radiology_test.new_bill') }}
@endsection
@section('header_toolbar')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-7">
            <h1 class="mb-0">@yield('title')</h1>
            <a href="#" onClick="history.back()"
               class="btn btn-outline-primary">{{ __('messages.common.back') }}</a>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    @include('layouts.errors')
                    @include('flash::message')
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-x-ray me-2"></i>Create New Radiology Test Request
                    </h5>
                </div>
                <div class="card-body p-4">
                    {{ Form::open(['route' => 'radiology.test.store', 'id' => 'createRadiologyTest']) }}
                    @include('radiology_tests.fields')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            // Prevent form submission if required fields are empty
            $('#createRadiologyTest').on('submit', function(e) {
                var patientId = $('select[name="patient_id"]').val();
                var doctorId = $('select[name="doctor_id"]').val();
                var caseId = $('select[name="case_id"]').val();
                var templateId = $('select[name="template_id[]"]').val();
                var reportDate = $('input[name="report_date[]"]').val();

                if (!patientId || !doctorId || !caseId || !templateId || !reportDate) {
                    e.preventDefault();
                    alert('Please fill in all required fields before submitting.');
                    return false;
                }

                // Show loading state - look for input[type="submit"] instead of button
                var submitBtn = $(this).find('input[type="submit"]');
                var originalText = submitBtn.val();
                submitBtn.prop('disabled', true).val('Creating...');

                // Re-enable after 5 seconds if form doesn't submit
                setTimeout(function() {
                    submitBtn.prop('disabled', false).val(originalText);
                }, 5000);
            });

            // Handle template selection
            $(document).on('change', '.template-select', function() {
                var templateId = $(this).val();
                var row = $(this).closest('tr');

                if (templateId) {
                    // Show loading indicator
                    row.find('.report-days').val('Loading...');
                    row.find('.amount').val('Loading...');

                    // Get template details via AJAX
                    $.get('/radiology-tests/get-template-config/' + templateId, function(data) {
                        if (data.success) {
                            var template = data.data;
                            row.find('.report-days').val(template.report_days || '');
                            row.find('.amount').val(template.standard_charge || '');

                            // Auto-fill report date based on report days
                            if (template.report_days && template.report_days > 0) {
                                try {
                                    var today = new Date();
                                    var reportDays = parseInt(template.report_days);

                                    if (isNaN(reportDays)) {
                                        console.error('Invalid report days:', template.report_days);
                                        return;
                                    }

                                    var reportDate = new Date(today);
                                    reportDate.setDate(today.getDate() + reportDays);

                                    // Ensure the date is valid
                                    if (!isNaN(reportDate.getTime())) {
                                        // Format date as YYYY-MM-DD for input field
                                        var year = reportDate.getFullYear();
                                        var month = String(reportDate.getMonth() + 1).padStart(2, '0');
                                        var day = String(reportDate.getDate()).padStart(2, '0');
                                        var formattedDate = year + '-' + month + '-' + day;

                                        console.log('Setting report date:', formattedDate, 'for template:', template.test_name);
                                        row.find('.report-date').val(formattedDate);
                                    } else {
                                        console.error('Invalid date calculated:', reportDate);
                                    }
                                } catch (error) {
                                    console.error('Error calculating report date:', error);
                                }
                            }

                            // Store form configuration for later use
                            row.find('.form-config').val(JSON.stringify(template.form_configuration || []));
                        } else {
                            row.find('.report-days').val('');
                            row.find('.amount').val('');
                            alert('Error loading template details. Please try again.');
                        }
                    }).fail(function() {
                        row.find('.report-days').val('');
                        row.find('.amount').val('');
                        alert('Error loading template details. Please try again.');
                    });
                } else {
                    row.find('.report-days').val('');
                    row.find('.amount').val('');
                    row.find('.report-date').val('{{ date('Y-m-d') }}');
                    row.find('.form-config').val('');
                }
            });

            // Handle add row functionality
            $(document).on('click', '.add-parameter-radiology-test-billing', function() {
                var container = $('.radiology-test-container');
                var rowCount = container.find('tr').length;
                var newRow = `
                    <tr>
                        <td>
                            <select name="template_id[]" class="form-select template-select" required>
                                <option value="">Select Template</option>
                                @foreach($templatesForSelect as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="form_configuration[]" class="form-config" value="">
                        </td>
                        <td>
                            <input type="text" name="report_days[]" class="form-control report-days" readonly>
                        </td>
                        <td>
                            <input type="date" name="report_date[]" class="form-control report-date" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                        </td>
                        <td>
                            <input type="text" name="amount[]" class="form-control amount" readonly>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                container.append(newRow);

                // Apply the same template selection logic to the new row
                var newRowElement = container.find('tr').last();
                newRowElement.find('.template-select').on('change', function() {
                    var templateId = $(this).val();
                    var row = $(this).closest('tr');

                    if (templateId) {
                        // Show loading indicator
                        row.find('.report-days').val('Loading...');
                        row.find('.amount').val('Loading...');

                        // Get template details via AJAX
                        $.get('/radiology-tests/get-template-config/' + templateId, function(data) {
                            if (data.success) {
                                var template = data.data;
                                row.find('.report-days').val(template.report_days || '');
                                row.find('.amount').val(template.standard_charge || '');

                                // Auto-fill report date based on report days
                                if (template.report_days && template.report_days > 0) {
                                    try {
                                        var today = new Date();
                                        var reportDays = parseInt(template.report_days);

                                        if (isNaN(reportDays)) {
                                            console.error('Invalid report days:', template.report_days);
                                            return;
                                        }

                                        var reportDate = new Date(today);
                                        reportDate.setDate(today.getDate() + reportDays);

                                        // Ensure the date is valid
                                        if (!isNaN(reportDate.getTime())) {
                                            // Format date as YYYY-MM-DD for input field
                                            var year = reportDate.getFullYear();
                                            var month = String(reportDate.getMonth() + 1).padStart(2, '0');
                                            var day = String(reportDate.getDate()).padStart(2, '0');
                                            var formattedDate = year + '-' + month + '-' + day;

                                            console.log('Setting report date:', formattedDate, 'for template:', template.test_name);
                                            row.find('.report-date').val(formattedDate);
                                        } else {
                                            console.error('Invalid date calculated:', reportDate);
                                        }
                                    } catch (error) {
                                        console.error('Error calculating report date:', error);
                                    }
                                }

                                // Store form configuration for later use
                                row.find('.form-config').val(JSON.stringify(template.form_configuration || []));
                            } else {
                                row.find('.report-days').val('');
                                row.find('.amount').val('');
                                alert('Error loading template details. Please try again.');
                            }
                        }).fail(function() {
                            row.find('.report-days').val('');
                            row.find('.amount').val('');
                            alert('Error loading template details. Please try again.');
                        });
                    } else {
                        row.find('.report-days').val('');
                        row.find('.amount').val('');
                        row.find('.report-date').val('');
                        row.find('.form-config').val('');
                    }
                });
            });

            // Handle remove row functionality
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection
