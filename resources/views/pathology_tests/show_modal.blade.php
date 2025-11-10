<div id="showPathologyTestBill" class="modal fade side-fade pathology-report-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">{{ __('messages.pathology_test.pathology_test_details') }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- Include Pathology Report CSS -->
                <link href="{{ asset('assets/css/pathology-report.css') }}" rel="stylesheet">

                <div class="pathology-report-container" id="pathology-report-content">
                    <!-- Pathology Report Header -->
                    <div class="pathology-report-header">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <!-- Logo placeholder -->
                                <div class="me-3">
                                    <div style="width: 60px; height: 60px; background: linear-gradient(45deg, #ff6b35, #f7931e); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 10px; text-align: center;">
                                        <div>
                                            <div style="font-size: 8px;">SD-GOLD</div>
                                            <div style="font-size: 6px;">CARDINAL</div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="pathology-report-title mb-0">
                                        CARDINAL NAMDINI MINING LTD, CLINIC LABORATORY
                                    </h4>
                                </div>
                            </div>
                            <!-- Print Button -->
                            <div class="no-print">
                                <button onclick="printLaboratoryReport()" class="btn btn-success me-2">
                                    <i class="fas fa-print"></i> Print Report
                                </button>
                                <a href="#" id="pathologyPdfLink" target="_blank" class="btn btn-outline-success">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </div>
                        <div class="text-center">
                            <h5 class="pathology-report-subtitle mb-0">
                                LABORATORY RESULTS
                            </h5>
                        </div>
                    </div>

                    <div class="pathology-report-body">
                        <!-- Administrative Details Section -->
                        <div class="pathology-admin-details">
                            <!-- First Row - Yellow Background -->
                            <div class="row mb-2" style="background: #fbbf24; padding: 8px 12px; border-radius: 4px; margin: 0;">
                                <div class="col-4">
                                    <span class="pathology-admin-label" style="font-weight: 600; color: #92400e; font-size: 12px; text-transform: uppercase;">DATE : <span id="showPathologyTestCreatedOn"></span></span>
                                </div>
                                <div class="col-4 text-center">
                                    <span class="pathology-admin-label" style="font-weight: 600; color: #92400e; font-size: 12px; text-transform: uppercase;">SPECIMEN : BLOOD</span>
                                </div>
                                <div class="col-4 text-end">
                                    <span class="pathology-admin-label" style="font-weight: 600; color: #92400e; font-size: 12px; text-transform: uppercase;">LAB NO : <span id="showPathologyBillNo"></span></span>
                                </div>
                            </div>

                            <!-- Second Row - White Background -->
                            <div class="row mb-2" style="background: white; padding: 8px 12px; border-radius: 4px; margin: 0; border: 1px solid #e5e7eb;">
                                <div class="col-6">
                                    <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">NAME OF PATIENT : <span id="showPathologyTestPatient"></span></span>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">AGE : <span id="showPathologyTestAge">N/A</span> YRS</span>
                                    <span class="pathology-admin-label ms-3" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">SEX : <span id="showPathologyTestSex">N/A</span></span>
                                </div>
                            </div>

                            <!-- Third Row - White Background -->
                            <div class="row mb-2" style="background: white; padding: 8px 12px; border-radius: 4px; margin: 0; border: 1px solid #e5e7eb;">
                                <div class="col-6">
                                    <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">DIAGNOSIS : <span id="showPathologyTestDiagnosis">N/A</span></span>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">TEST REQUESTED : <span id="showPathologyTestRequested">N/A</span></span>
                                </div>
                            </div>

                            <!-- Fourth Row - White Background -->
                            <div class="row mb-3" style="background: white; padding: 8px 12px; border-radius: 4px; margin: 0; border: 1px solid #e5e7eb;">
                                <div class="col-6">
                                    <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">NAME OF CLINICIAN : <span id="showPathologyTestDoctor"></span></span>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="pathology-admin-label" style="font-weight: 600; color: #374151; font-size: 12px; text-transform: uppercase;">TEST PERFORMED BY : <span id="showPathologyTestPerformedBy">N/A</span></span>
                                </div>
                            </div>
                        </div>

                        <!-- Test Results Sections -->
                        <div id="pathology-test-results-container">
                            <!-- Test results will be populated here by JavaScript -->
                        </div>

                        <!-- Signature Section -->
                        <div class="signature-section">
                            <div class="row">
                                <div class="col-6">
                                    <div class="signature-line"></div>
                                    <div class="signature-label">LABORATORY TECHNICIAN</div>
                                </div>
                                <div class="col-6 text-end">
                                    <div class="signature-line" style="margin-left: auto;"></div>
                                    <div class="signature-label">PATHOLOGIST</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printLaboratoryReport() {
        // Store current page title
        const originalTitle = document.title;

        // Set page title for print
        const billNo = document.getElementById('showPathologyBillNo')?.textContent || 'Unknown';
        document.title = 'Laboratory Test Report - ' + billNo;

        // Hide all unnecessary elements before printing
        const elementsToHide = document.querySelectorAll('.no-print, .header, .sidebar, .footer, .breadcrumb, .btn, .navbar, .dropdown, .alert, .modal-header, .btn-close');
        elementsToHide.forEach(el => {
            if (el) el.style.display = 'none';
        });

        // Focus on the report content
        const reportContent = document.getElementById('pathology-report-content');
        if (reportContent) {
            reportContent.style.display = 'block';
        }

        // Print the page
        window.print();

        // Restore original title and elements after printing
        setTimeout(() => {
            document.title = originalTitle;
            elementsToHide.forEach(el => {
                if (el) el.style.display = '';
            });
        }, 1000);
    }

    // Add print event listener for better control
    window.addEventListener('beforeprint', function() {
        // Ensure the report is properly formatted before printing
        const reportContainer = document.getElementById('pathology-report-content');
        if (reportContainer) {
            reportContainer.style.background = 'white';
            reportContainer.style.margin = '0';
            reportContainer.style.padding = '20px';
        }
    });

    window.addEventListener('afterprint', function() {
        // Restore any changes made for printing
        const reportContainer = document.getElementById('pathology-report-content');
        if (reportContainer) {
            reportContainer.style.background = '';
            reportContainer.style.margin = '';
            reportContainer.style.padding = '';
        }
    });
</script>
