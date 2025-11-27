@extends('layouts.app')

@section('title', 'Laboratory Attendance Report')

@section('content')

{{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">


<div class="container-fluid">
    <h2 class="mb-4">Laboratory Monthly Report - Attendance</h2>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Attendance Frequency by Company (Month: April 2025)</h6>
                </div>
                <div class="card-body">

                    <p class="mb-1"><strong>Month:</strong> APRIL</p>
                    <p class="mb-4"><strong>Year:</strong> 2025</p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="attendanceTable" width="100%">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>COMPANY</th>
                                    <th>FREQUENCY</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>1.</td><td>CNML</td><td>28</td></tr>
                                <tr><td>2.</td><td>GOLDEN DYNASTY</td><td>26</td></tr>
                                <tr><td>3.</td><td>CSC</td><td>13</td></tr>
                                <tr><td>4.</td><td>ATS</td><td>11</td></tr>
                                <tr><td>5.</td><td>E & P</td><td>7</td></tr>
                                <tr><td>6.</td><td>HONJOE</td><td>3</td></tr>
                                <tr><td>7.</td><td>ENFI</td><td>3</td></tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">TOTAL</th>
                                    <th>91</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4">
                        <p><strong>Compiled By:</strong> NYAME ABU BOATENG/ELVIS ACHEAMPONG [cite: 8]</p>
                        <p><strong>Position:</strong> MEDICAL LABORATORY TECHNICIAN [cite: 9]</p>
                        <p><strong>Date:</strong> 1ST MAY 2025 [cite: 10]</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- jQuery + DataTables JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#attendanceTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true
        });
    });
</script>

@endsection
