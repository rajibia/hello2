@extends('layouts.app') <!-- Or the layout you are using -->

@section('content')
<div class="container">
    <h2>Pathology Tests Templates</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Test Name</th>
                <th>Short Name</th>
                <th>Test Type</th>
                <th>Category ID</th>
                <th>Unit</th>
                <th>Subcategory</th>
                <th>Method</th>
                <th>Report Days</th>
                <th>Charge Category ID</th>
                <th>Standard Charge</th>
            </tr>
        </thead>
        <tbody>
            @if($pathologyTests->isEmpty())
                <tr>
                    <td colspan="11" class="text-center">No Pathology Tests Found</td>
                </tr>
            @else
                @foreach($pathologyTests as $test)
                    <tr>
                        <td>{{ $test->test_name }}</td>
                        <td>{{ $test->short_name }}</td>
                        <td>{{ $test->test_type }}</td>
                        <td>{{ $test->category_id }}</td>
                        <td>{{ $test->unit }}</td>
                        <td>{{ $test->subcategory }}</td>
                        <td>{{ $test->method }}</td>
                        <td>{{ $test->report_days }}</td>
                        <td>{{ $test->charge_category_id }}</td>
                        <td>{{ $test->standard_charge }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection
