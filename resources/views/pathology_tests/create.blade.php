@extends('layouts.app')
@section('title')
    {{ __('messages.pathology_test.new_bill') }}
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
                        <i class="fas fa-flask me-2"></i>Create New Pathology Test Request
                    </h5>
                </div>
                <div class="card-body p-4">
                    {{ Form::open(['route' => 'pathology.test.store', 'id' => 'createPathologyTest', 'method' => 'POST']) }}
                    @include('pathology_tests.fields')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/pathology_tests/create-edit.js') }}"></script>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            console.log('Pathology test create form initializing...');

            // Debug: Log form data
            console.log('Patient ID:', '{{ $patient_id }}');
            console.log('IPD ID:', '{{ $ipd_id }}');
            console.log('Case ID:', '{{ $case_id }}');
            console.log('Available templates:', {{ count($templatesForSelect) }});

            // Prevent form submission if required fields are empty
            $('#createPathologyTest').on('submit', function(e) {
                e.preventDefault();

                console.log('Form submission started...');

                // Get form data
                var patientId = $('select[name="patient_id"]').val();
                var doctorId = $('select[name="doctor_id"]').val();
                var caseId = $('select[name="case_id"]').val();

                console.log('Form data:', { patientId, doctorId, caseId });

                // Check if at least one template is selected
                var selectedTemplates = $('select[name="template_id[]"]').filter(function() {
                    return $(this).val() !== '';
                });

                console.log('Selected templates:', selectedTemplates.length);

                if (!patientId || !doctorId || !caseId) {
                    alert('Please fill in all required fields (Patient, Doctor, Case) before submitting.');
                    return false;
                }

                if (selectedTemplates.length === 0) {
                    alert('Please select at least one test template.');
                    return false;
                }

                // Check if all selected templates have report dates
                var hasInvalidDates = false;
                selectedTemplates.each(function() {
                    var row = $(this).closest('tr');
                    var reportDate = row.find('input[name="report_date[]"]').val();
                    if (!reportDate) {
                        hasInvalidDates = true;
                        return false; // break the loop
                    }
                });

                if (hasInvalidDates) {
                    alert('Please set report date for all selected tests.');
                    return false;
                }

                console.log('Form validation passed, submitting...');

                // Show loading state
                var submitBtn = $(this).find('input[type="submit"]');
                var originalText = submitBtn.val();
                submitBtn.prop('disabled', true).val('Creating...');

                // Ensure form is submitted normally, not via AJAX
                console.log('Submitting form normally (not AJAX)...');

                // Submit the form using regular form submission
                this.submit();
            });

            // Handle template selection with enhanced functionality
            $(document).on('change', '.template-select', function() {
                var templateId = $(this).val();
                var row = $(this).closest('tr');

                console.log('Template selected:', templateId);

                if (templateId) {
                    // Show loading indicator
                    row.find('.report-days').val('Loading...');
                    row.find('.amount').val('Loading...');

                    // Get template details via AJAX
                    $.get('/pathology-tests/get-template-config/' + templateId, function(data) {
                        console.log('Template data received:', data);

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
                    }).fail(function(xhr, status, error) {
                        console.error('AJAX error:', error);
                        row.find('.report-days').val('');
                        row.find('.amount').val('');
                        row.find('.template-type-badge').text('-').removeClass().addClass('badge bg-secondary');
                        alert('Error loading template details. Please try again.');
                    });
                } else {
                    row.find('.report-days').val('');
                    row.find('.amount').val('');
                    row.find('.report-date').val('');
                    row.find('.form-config').val('');
                }
            });

            // Handle add row functionality
            $(document).on('click', '.add-parameter-test-billing', function() {
                console.log('Adding new test row...');

                var container = $('.pathology-test-container');
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
                            <input type="date" name="report_date[]" class="form-control report-date" required min="{{ date('Y-m-d') }}" readonly>
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
                        $.get('/pathology-tests/get-template-config/' + templateId, function(data) {
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
                                row.find('.template-type-badge').text('-').removeClass().addClass('badge bg-secondary');
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



            // Initialize form validation
            console.log('Pathology test create form initialized successfully');
        });
    </script>
@endsection
