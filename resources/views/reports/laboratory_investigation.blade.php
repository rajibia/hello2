@extends('layouts.app')

@section('title', 'Laboratory Investigations Report')

{{-- DataTables CSS --}}
@section('page_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <style>
        .dataTables_wrapper .row:first-child {
            margin-bottom: 15px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Laboratory Monthly Report - Test Investigations Done</h2>

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Test Investigations Done (Month: April 2025)</h6>
                    </div>
                    <div class="card-body">
                        

                        {{-- Report Details --}}
                        <p class="mb-1"><strong>Month:</strong> APRIL</p>
                        <p class="mb-4"><strong>Year:</strong> 2025</p>

                        {{-- Investigations Table --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="investigationTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>ANALYTE</th>
                                        <th class="text-center">NEGATIVE</th>
                                        <th class="text-center">POSITIVE</th>
                                        <th class="text-center">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>FULL BLOOD COUNT (FBC)</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">108</td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>MALARIA TEST</td>
                                        <td class="text-center">107</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center">108</td>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>TYPHOID TEST</td>
                                        <td class="text-center">88</td>
                                        <td class="text-center">30</td>
                                        <td class="text-center">118</td>
                                    </tr>
                                    <tr>
                                        <td>4.</td>
                                        <td>H. PYLORI</td>
                                        <td class="text-center">48</td>
                                        <td class="text-center">20</td>
                                        <td class="text-center">68</td>
                                    </tr>
                                    <tr>
                                        <td>5.</td>
                                        <td>BLOOD GLUCOSE</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">21</td>
                                    </tr>
                                    <tr>
                                        <td>6.</td>
                                        <td>URINE R/E</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">10</td>
                                    </tr>
                                    <tr>
                                        <td>7.</td>
                                        <td>BLOOD GROUP</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">6</td>
                                    </tr>
                                    <tr>
                                        <td>8.</td>
                                        <td>UPT</td>
                                        <td class="text-center">5</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">5</td>
                                    </tr>
                                    <tr>
                                        <td>9.</td>
                                        <td>STOOL R/E</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">1</td>
                                    </tr>
                                    <tr>
                                        <td>10.</td>
                                        <td>HEPATITIS B TEST</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">1</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-right">TOTALS</th>
                                        {{-- Sum of 107 + 88 + 48 + 5 = 248 --}}
                                        <th class="text-center">248</th>
                                        {{-- Sum of 1 + 30 + 20 = 51 --}}
                                        <th class="text-center">51</th>
                                        {{-- Sum of all totals = 446 --}}
                                        <th class="text-center">446</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        {{-- Compiler Information --}}
                        <div class="mt-4">
                            <p><strong>Compiled By:</strong> NYAME ABU BOATENG/ELVIS ACHEAMPONG</p>
                            <p><strong>Position:</strong> MEDICAL LABORATORY TECHNICIAN</p>
                            <p><strong>Date:</strong> 1ST MAY 2025</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- DataTables JavaScript and Initialization Script --}}
@section('scripts')
    {{-- Ensure jQuery is loaded before this script block --}}
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTables on the investigation table ID
            $('#investigationTable').DataTable({
                "paging": false, 
                "searching": true, 
                "ordering": true,
                "info": false 
            });
        });
    </script>
@endsection