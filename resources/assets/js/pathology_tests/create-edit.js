document.addEventListener('turbo:load', loadPathologyTestData)

function loadPathologyTestData() {
    if (!$('#createPathologyTest').length && !$('#editPathologyTest').length) {
        return
    }

    $('.price-input').trigger('input');
    $('.pathologyCategories,.pChargeCategories').select2({
        width: '100%',
    });

    $('#createPathologyTest, #editPathologyTest').find('input:text:visible:first').focus();

}


listenChange('.pChargeCategories', function (event) {
    let chargeCategoryId = $(this).val();
    (chargeCategoryId !== '') ? getPathologyTestStandardCharge(chargeCategoryId) : $(
        '.pathologyStandardCharge').val('');
});

function getPathologyTestStandardCharge(id) {
    $.ajax({
        url: $('.pathologyTestActionURL').val() + '/get-standard-charge' + '/' + id,
        method: 'get',
        cache: false,
        success: function (result) {
            if (result !== '') {
                $('.pathologyStandardCharge').val(result.data);
                $('.price-input').trigger('input');
            }
        },
    });
}

// Handle pathology test template selection
listenChange('.template-select', function (event) {
    let testId = $(this).val();
    let currentRow = $(this).closest('tr');
    let reportDaysInput = currentRow.find('input[name="report_days[]"]');
    let reportDateInput = currentRow.find('input[name="report_date[]"]');
    let amountInput = currentRow.find('input[name="amount[]"]');
    let templateTypeBadge = currentRow.find('.template-type-badge');
    let formConfigInput = currentRow.find('input[name="form_configuration[]"]');

    if (testId === '' || testId === null) {
        reportDaysInput.val('');
        reportDateInput.val('');
        amountInput.val('');
        templateTypeBadge.text('-').removeClass().addClass('template-type-badge badge bg-secondary');
        formConfigInput.val('');
        calculatePathologyTotals();
        return;
    }

    $.ajax({
        url: '/pathology-tests-templates/details/' + testId,
        method: 'GET',
        cache: false,
        success: function (result) {
            if (result) {
                reportDaysInput.val(result.report_days);
                reportDateInput.val(result.report_date);
                amountInput.val(result.amount);

                // Update template type badge
                let templateType = result.form_configuration?.table_type || 'standard';
                let templateTypeLabel = getTemplateTypeLabel(templateType);
                let badgeClass = getTemplateTypeBadgeClass(templateType);

                templateTypeBadge.text(templateTypeLabel).removeClass().addClass('template-type-badge badge ' + badgeClass);

                // Store form configuration
                formConfigInput.val(JSON.stringify(result.form_configuration || {}));

                // Trigger calculation
                calculatePathologyTotals();
            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching pathology template details:', error);
        }
    });
});

// Helper function to get template type label
function getTemplateTypeLabel(templateType) {
    const labels = {
        'standard': 'Standard',
        'simple': 'Simple',
        'specimen': 'Specimen',
        'species_dependent': 'Species',
        'field_value_multi': 'Field-Value'
    };
    return labels[templateType] || 'Standard';
}

// Helper function to get badge class for template type
function getTemplateTypeBadgeClass(templateType) {
    const classes = {
        'standard': 'bg-primary',
        'simple': 'bg-success',
        'specimen': 'bg-warning',
        'species_dependent': 'bg-info',
        'field_value_multi': 'bg-secondary'
    };
    return classes[templateType] || 'bg-primary';
}

// Calculate pathology test totals
function calculatePathologyTotals() {
    let subtotal = 0;

    // Calculate subtotal from all amount inputs
    $('.amount_summand').each(function() {
        let amount = parseFloat($(this).val()) || 0;
        subtotal += amount;
    });

    // Update subtotal display
    $('#sub_total_add_path').text(subtotal.toFixed(2));
    $('#sub_total_edit_path').text(subtotal.toFixed(2));

    // Calculate discount
    let discountPercent = parseFloat($('#discount_input_add_path').val()) || parseFloat($('#discount_input_edit_path').val()) || 0;
    let discountAmount = (subtotal * discountPercent) / 100;

    // Update discount display
    $('#discount_add_path').text(discountAmount.toFixed(2));
    $('#discount_edit_path').text(discountAmount.toFixed(2));
    $('#discount_hidden_add_path').val(discountAmount.toFixed(2));
    $('#discount_hidden_edit_path').val(discountAmount.toFixed(2));

    // Calculate total
    let total = subtotal - discountAmount;

    // Update total display
    $('#total_add_path').text(total.toFixed(2));
    $('#total_edit_path').text(total.toFixed(2));
    $('#total_hidden_add_path').val(total.toFixed(2));
    $('#total_hidden_edit_path').val(total.toFixed(2));
}

// Listen for discount changes
listenChange('#discount_input_add_path, #discount_input_edit_path', function() {
    calculatePathologyTotals();
});

// Listen for amount changes
listenChange('.amount_summand', function() {
    calculatePathologyTotals();
});

// Listen for keyup events on discount input
listenKeyup('#discount_input_add_path, #discount_input_edit_path', function() {
    calculatePathologyTotals();
});

// Handle pathology test preview button click
listenClick('.showPathologyTestBillBtn', function (event) {
    event.preventDefault();
    var pathologyTestId = $(event.currentTarget).attr('data-id');

    console.log('Modal button clicked for test ID:', pathologyTestId);

    $.ajax({
        url: $('#pathologyTestBillShowUrl').val() + '/' + pathologyTestId,
        method: 'GET',
        cache: false,
        success: function (result) {
            console.log('Modal data received:', result);

            if (result.success) {
                var data = result.data;

                // Populate basic modal fields
                $('#showPathologyBillNo').text(data.lab_number || data.bill_no);
                $('#showPathologyTestPatient').text(data.patient_name);
                $('#showPathologyTestDoctor').text(data.doctor);
                $('#showPathologyTestCreatedOn').text(data.created_on);
                $('#showPathologyTestAge').text(data.age || 'N/A');
                $('#showPathologyTestSex').text(data.sex || 'N/A');
                $('#showPathologyTestDiagnosis').text(data.diagnosis || 'N/A');
                $('#showPathologyTestRequested').text(data.test_requested || 'N/A');
                $('#showPathologyTestPerformedBy').text(data.performed_by || 'N/A');

                // Update PDF link
                $('#pathologyPdfLink').attr('href', '/pathology-tests/' + pathologyTestId + '/pdf');

                // Populate test results sections
                var resultsContainer = $('#pathology-test-results-container');
                resultsContainer.empty();

                if (data.test_items && data.test_items.length > 0) {
                    console.log('Processing test items:', data.test_items);
                    data.test_items.forEach(function(item, index) {
                        console.log('Processing item:', item);
                        var resultsSection = generateTestResultsSection(item);
                        resultsContainer.append(resultsSection);
                    });
                } else {
                    resultsContainer.append(`
                        <div class="alert alert-warning" style="margin: 20px; background: #fffbeb; border: 1px solid #fbbf24; color: #92400e; padding: 12px 16px; border-radius: 4px;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No test items found for this pathology test.
                        </div>
                    `);
                }

                // Show the modal
                $('#showPathologyTestBill').modal('show');
            } else {
                console.error('Error loading pathology test details:', result.message);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error loading pathology test details:', error);
        }
    });
});

// Function to generate test results section based on template type
function generateTestResultsSection(item) {
    var templateType = item.template_type || 'standard';
    var formConfig = item.form_configuration || {};
    var testResults = item.test_results || {};

    // Debug logging for all template types
    console.log('=== TEMPLATE DEBUG START ===');
    console.log('Item template_type:', item.template_type);
    console.log('Item template_type_badge:', item.template_type_badge);
    console.log('FormConfig table_type:', formConfig.table_type);
    console.log('FormConfig field_value_config:', formConfig.field_value_config);
    console.log('Final templateType:', templateType);
    console.log('Full item:', item);
    console.log('Full formConfig:', formConfig);
    console.log('=== TEMPLATE DEBUG END ===');

    // Ensure formConfig is an object
    if (typeof formConfig === 'string') {
        try {
            formConfig = JSON.parse(formConfig);
        } catch (e) {
            formConfig = {};
        }
    }

    // Ensure testResults is an object
    if (typeof testResults === 'string') {
        try {
            testResults = JSON.parse(testResults);
        } catch (e) {
            testResults = {};
        }
    }

    // PRIORITY: Check formConfig first for Field-Value Multi-Column
    if (formConfig.table_type === 'field_value_multi' || formConfig.field_value_config) {
        templateType = 'field_value_multi';
        console.log('✅ FORCED Field-Value Multi-Column detection from formConfig');
    }
    // Fallback detection for Field-Value Multi-Column
    else if (templateType === 'standard' && formConfig.table_type === 'field_value_multi') {
        templateType = 'field_value_multi';
        console.log('✅ Detected Field-Value Multi-Column from formConfig.table_type');
    }
    // Additional fallback - check if it's a Field-Value Multi-Column by checking field_value_config
    else if (templateType === 'standard' && formConfig.field_value_config) {
        templateType = 'field_value_multi';
        console.log('✅ Detected Field-Value Multi-Column from field_value_config presence');
    }

    var resultsSection = '';

    // For Field-Value Multi-Column, don't show the standard header
    if (templateType !== 'field_value_multi') {
        resultsSection = `
            <div class="pathology-results-section">
                <div class="pathology-results-header">
                    <h6 class="mb-0">${item.test_name}</h6>
                    ${item.template_type_badge ? `<span class="badge ${item.template_type_class} ms-2" style="font-size: 10px;">${item.template_type_badge}</span>` : ''}
                </div>
        `;
    } else {
        resultsSection = '<div class="pathology-results-section">';
    }

    // Generate content based on template type
    console.log('🎯 FINAL DECISION: Generating content for template type:', templateType);
    switch(templateType) {
        case 'field_value_multi':
            console.log('✅ Using Field-Value Multi-Column generator');
            resultsSection += generateFieldValueMultiSection(item, formConfig, testResults);
            break;
        case 'species_dependent':
            console.log('Using Species Dependent generator');
            resultsSection += generateSpeciesDependentSection(item, formConfig, testResults);
            break;
        case 'specimen':
            console.log('Using Specimen generator');
            resultsSection += generateSpecimenSection(item, formConfig, testResults);
            break;
        case 'simple':
            console.log('Using Simple generator');
            resultsSection += generateSimpleSection(item, formConfig, testResults);
            break;
        case 'standard':
        default:
            console.log('❌ Using Standard generator (this might be wrong!)');
            resultsSection += generateStandardSection(item, formConfig, testResults);
            break;
    }

    resultsSection += '</div>';
    return resultsSection;
}

// Generate Field-Value Multi-Column section
function generateFieldValueMultiSection(item, formConfig, testResults) {
    console.log('generateFieldValueMultiSection called with:', {
        item: item,
        formConfig: formConfig,
        testResults: testResults
    });

    var fieldValueConfig = formConfig.field_value_config || {};
    var columnsPerRow = fieldValueConfig.columns || 4;
    var separator = fieldValueConfig.separator || ': ';
    var fields = formConfig.fields || [];
    var specimenName = formConfig.specimen_name || item.test_type || 'SPECIMEN';

    console.log('Field-Value Multi-Column config:', {
        fieldValueConfig: fieldValueConfig,
        columnsPerRow: columnsPerRow,
        separator: separator,
        fields: fields,
        specimenName: specimenName
    });

    // Ensure fields is an array
    if (!Array.isArray(fields)) {
        fields = [];
    }

    var content = `
        <!-- Test Results Header - Outside Table -->
        <div class="text-center">
            <h5 style="font-weight: bold; color: #92400e; font-size: 16px; margin: 0; padding: 10px 0;">
                TEST RESULTS FOR ${item.test_name.toUpperCase()}
            </h5>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size: 12px; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #fef3c7;">
                        <th colspan="${columnsPerRow}" class="text-center" style="font-weight: bold; color: #92400e; font-size: 12px; padding: 8px 12px; border: 1px solid #ddd;">
                            SPECIMEN: ${item.test_type ? item.test_type.toUpperCase() : 'SPECIMEN'}
                        </th>
                    </tr>
                </thead>
                <tbody>
    `;

    if (fields.length > 0) {
        console.log('Processing fields:', fields);

        // Group fields into chunks based on columns per row (exactly like PHP array_chunk)
        var fieldChunks = [];
        for (var i = 0; i < fields.length; i += columnsPerRow) {
            fieldChunks.push(fields.slice(i, i + columnsPerRow));
        }

        fieldChunks.forEach(function(row, rowIndex) {
            console.log('Processing row:', rowIndex, 'with fields:', row);

            var rowBackground = rowIndex % 2 === 0 ? '#fef3c7' : 'white';
            content += `<tr style="background-color: ${rowBackground};">`;

            row.forEach(function(field, fieldIndex) {
                console.log('Processing field:', field);

                // Ensure field is an object with required properties
                if (typeof field === 'object' && field !== null) {
                    var result = testResults[field.name] || null;
                    var fieldLabel = field.label || 'PARAMETER';
                    var fieldUnit = field.unit || '';

                    console.log('Field data:', {
                        name: field.name,
                        label: fieldLabel,
                        result: result,
                        unit: fieldUnit
                    });

                    content += `
                        <td style="padding: 8px 12px; border: 1px solid #ddd; vertical-align: top; background-color: ${rowBackground}; text-align: left;">
                            <div class="field-value-pair">
                                <span style="font-weight: bold; color: #dc2626;">
                                    ${fieldLabel.toUpperCase()}${separator}
                                </span>
                                <span style="color: #000000; font-weight: normal;">
                                    ${result !== null && result !== '' ? result.toUpperCase() : '<span style="color: #7f8c8d;">_________________</span>'}
                                </span>
                                ${fieldUnit ? `<div style="color: #7f8c8d; font-size: 10px; margin-top: 2px;">Unit: ${fieldUnit}</div>` : ''}
                            </div>
                        </td>
                    `;
                } else {
                    console.log('Invalid field data:', field);
                    content += `<td style="padding: 8px 12px; border: 1px solid #ddd; vertical-align: top; background-color: ${rowBackground};"></td>`;
                }
            });

            // Fill remaining cells if needed (exactly like PHP @for loop)
            for (var i = row.length; i < columnsPerRow; i++) {
                content += `<td style="padding: 8px 12px; border: 1px solid #ddd; vertical-align: top; background-color: ${rowBackground};">
                    <!-- Empty column - left blank for clean report appearance -->
                </td>`;
            }

            content += '</tr>';
        });
    } else {
        console.log('No fields configured');
        content += `
            <tr style="background-color: white;">
                <td colspan="${columnsPerRow}" class="text-center" style="padding: 20px; color: #7f8c8d; border: 1px solid #ddd;">
                    <!-- No fields configured - left blank for clean report appearance -->
                </td>
            </tr>
        `;
    }

    content += `
                </tbody>
            </table>
        </div>
    `;

    console.log('Generated content:', content);
    return content;
}

// Generate Species Dependent section
function generateSpeciesDependentSection(item, formConfig, testResults) {
    var speciesConfig = formConfig.species_config || {};
    var results = speciesConfig.results || '';

    var content = `
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size: 12px; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #fef3c7;">
                        <th style="width: 20%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">RESULTS</th>
                        <th style="width: 20%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">SPECIES</th>
                        <th style="width: 20%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">STAGE</th>
                        <th style="width: 20%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">COUNT</th>
                        <th style="width: 20%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">UNIT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background-color: white;">
                        <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd;">
                            ${testResults.results ? testResults.results.toUpperCase() : '-'}
                        </td>
                        <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd;">
                            ${testResults.species && testResults.species !== 'N/A' ? testResults.species.toUpperCase() : (testResults.species === 'N/A' ? '<span style="color: #9ca3af;">N/A</span>' : '<span style="color: #9ca3af;">-</span>')}
                        </td>
                        <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd;">
                            ${testResults.stage && testResults.stage !== 'N/A' ? testResults.stage.toUpperCase() : (testResults.stage === 'N/A' ? '<span style="color: #9ca3af;">N/A</span>' : '<span style="color: #9ca3af;">-</span>')}
                        </td>
                        <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd;">
                            ${testResults.count || '<span style="color: #9ca3af;">-</span>'}
                        </td>
                        <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd;">
                            ${testResults.unit ? testResults.unit.toUpperCase() : '<span style="color: #9ca3af;">-</span>'}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;

    return content;
}

// Generate Specimen section
function generateSpecimenSection(item, formConfig, testResults) {
    var specimenName = formConfig.specimen_name || 'SPECIMEN';
    var fields = formConfig.fields || [];

    // Ensure fields is an array
    if (!Array.isArray(fields)) {
        fields = [];
    }

    var content = `
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size: 12px; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #fef3c7;">
                        <th style="width: 20%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">${specimenName.toUpperCase()}</th>
                        <th style="width: 30%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">RESULTS</th>
                        <th style="width: 25%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">REFERENCE RANGE</th>
                        <th style="width: 15%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">FLAG</th>
                        <th style="width: 10%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">UNIT</th>
                    </tr>
                </thead>
                <tbody>
    `;

    if (fields.length > 0) {
        fields.forEach(function(field, index) {
            // Ensure field is an object with required properties
            if (typeof field === 'object' && field !== null) {
                var fieldValue = testResults[field.name] || '';
                var referenceRange = '';
                var flag = '';
                var flagClass = '';
                var fieldLabel = field.label || field.name || 'Unknown';
                var fieldUnit = field.unit || '';

                if (field.reference_min && field.reference_max && fieldValue !== '') {
                    referenceRange = field.reference_min + ' - ' + field.reference_max;
                    var resultValue = parseFloat(fieldValue);
                    var minValue = parseFloat(field.reference_min);
                    var maxValue = parseFloat(field.reference_max);

                    if (resultValue < minValue) {
                        flag = 'LOW';
                        flagClass = 'flag-low';
                    } else if (resultValue > maxValue) {
                        flag = 'HIGH';
                        flagClass = 'flag-high';
                    } else {
                        flag = 'NORMAL';
                        flagClass = 'flag-normal';
                    }
                }

                var rowBackground = index % 2 === 0 ? '#fef3c7' : 'white';

                content += `
                    <tr style="background-color: ${rowBackground};">
                        <td style="font-weight: 600; text-align: left; background: ${rowBackground}; padding: 8px 12px; border: 1px solid #ddd;">${fieldLabel.toUpperCase()}</td>
                        <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd; background: ${rowBackground};">
                            ${fieldValue !== '' ? fieldValue.toUpperCase() : '<span style="color: #9ca3af;">-</span>'}
                        </td>
                        <td style="text-align: center; font-size: 12px; color: #6b7280; padding: 8px 12px; border: 1px solid #ddd; background: ${rowBackground};">
                            ${referenceRange || '<span style="color: #9ca3af;">-</span>'}
                        </td>
                        <td style="text-align: center; padding: 8px 12px; border: 1px solid #ddd; background: ${rowBackground};">
                            ${flag ? `<span class="pathology-flag ${flagClass}">${flag}</span>` : '<span style="color: #9ca3af;">-</span>'}
                        </td>
                        <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd; background: ${rowBackground};">
                            ${fieldUnit ? fieldUnit.toUpperCase() : '<span style="color: #9ca3af;">-</span>'}
                        </td>
                    </tr>
                `;
            }
        });
    }

    content += `
                </tbody>
            </table>
        </div>
    `;

    return content;
}

// Generate Simple section
function generateSimpleSection(item, formConfig, testResults) {
    var fields = formConfig.fields || [];

    // Ensure fields is an array
    if (!Array.isArray(fields)) {
        fields = [];
    }

    var content = `
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size: 12px; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #fef3c7;">
                        <th style="width: 50%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">PARAMETER</th>
                        <th style="width: 50%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">RESULT</th>
                    </tr>
                </thead>
                <tbody>
    `;

    if (fields.length > 0) {
        fields.forEach(function(field, index) {
            // Ensure field is an object with required properties
            if (typeof field === 'object' && field !== null) {
                var fieldValue = testResults[field.name] || '';
                var fieldLabel = field.label || field.name || 'Unknown';
                var rowBackground = index % 2 === 0 ? '#fef3c7' : 'white';

                content += `
                    <tr style="background-color: ${rowBackground};">
                        <td style="font-weight: 600; text-align: left; background: ${rowBackground}; padding: 8px 12px; border: 1px solid #ddd;">${fieldLabel.toUpperCase()}</td>
                        <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd; background: ${rowBackground};">
                            ${fieldValue !== '' ? fieldValue.toUpperCase() : '<span style="color: #9ca3af;">-</span>'}
                        </td>
                    </tr>
                `;
            }
        });
    }

    content += `
                </tbody>
            </table>
        </div>
    `;

    return content;
}

// Generate Standard section
function generateStandardSection(item, formConfig, testResults) {
    var layoutType = formConfig.layout_type || 'single_row';
    var columnsPerRow = formConfig.columns_per_row || 1;
    var fields = formConfig.fields || [];

    // Ensure fields is an array
    if (!Array.isArray(fields)) {
        fields = [];
    }

    var content = `
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size: 12px; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #fef3c7;">
                        <th style="width: 30%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">PARAMETER</th>
                        <th style="width: 30%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">RESULT</th>
                        <th style="width: 25%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">REFERENCE RANGE</th>
                        <th style="width: 15%; color: #202020; font-weight: 600; text-transform: uppercase; padding: 8px 12px; border: 1px solid #ddd;">UNIT</th>
                    </tr>
                </thead>
                <tbody>
    `;

    if (fields.length > 0) {
        fields.forEach(function(field, index) {
            // Ensure field is an object with required properties
            if (typeof field === 'object' && field !== null) {
                var fieldValue = testResults[field.name] || '';
                var referenceRange = '';
                var fieldLabel = field.label || field.name || 'Unknown';
                var fieldUnit = field.unit || '';

                if (field.reference_min && field.reference_max) {
                    referenceRange = field.reference_min + ' - ' + field.reference_max;
                }

                var rowBackground = index % 2 === 0 ? '#fef3c7' : 'white';

                content += `
                    <tr style="background-color: ${rowBackground};">
                        <td style="font-weight: 600; text-align: left; background: ${rowBackground}; padding: 8px 12px; border: 1px solid #ddd;">${fieldLabel.toUpperCase()}</td>
                        <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd; background: ${rowBackground};">
                            ${fieldValue !== '' ? fieldValue.toUpperCase() : '<span style="color: #9ca3af;">-</span>'}
                        </td>
                        <td style="text-align: center; font-size: 12px; color: #6b7280; padding: 8px 12px; border: 1px solid #ddd; background: ${rowBackground};">
                            ${referenceRange || '<span style="color: #9ca3af;">-</span>'}
                        </td>
                        <td style="text-align: center; font-weight: 500; color: #374151; padding: 8px 12px; border: 1px solid #ddd; background: ${rowBackground};">
                            ${fieldUnit ? fieldUnit.toUpperCase() : '<span style="color: #9ca3af;">-</span>'}
                        </td>
                    </tr>
                `;
            }
        });
    }

    content += `
                </tbody>
            </table>
        </div>
    `;

    return content;
}

// Handle edit pathology test button click (for IPD/OPD pages)
listenClick('.editPathologyTestBillBtn', function (event) {
    event.preventDefault();
    var pathologyTestId = $(event.currentTarget).attr('data-id');

    // Redirect to edit page
    window.location.href = $('#pathologyTestBillEditUrl').val() + '/' + pathologyTestId;
});
