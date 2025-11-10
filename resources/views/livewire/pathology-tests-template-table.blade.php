<div class="card my-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Pathology Test Templates</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
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
                @forelse($pathologyTests as $test)
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
                @empty
                    <tr>
                        <td colspan="11" class="text-center">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
