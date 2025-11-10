<div class="container">
    <h2 class="text-center">Pathology Tests Templates</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Select</th>
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
            @if($pathologyTestTemplates->isEmpty())
                <tr>
                    <td colspan="11" class="text-center">No Pathology Tests Found</td>
                </tr>
            @else
                @foreach($pathologyTestTemplates as $test)
                    <tr>
                        <td><input type="checkbox" class="test-checkbox" data-test='@json($test)'></td>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.test-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const test = JSON.parse(this.getAttribute('data-test'));

            if (this.checked) {
                // Populate fields in fields.blade.php
                document.querySelector('input[name="test_name"]').value = test.test_name;
                document.querySelector('input[name="short_name"]').value = test.short_name;
                document.querySelector('input[name="test_type"]').value = test.test_type;
                document.querySelector('select[name="category_id"]').value = test.category_id;
                document.querySelector('input[name="unit"]').value = test.unit;
                document.querySelector('input[name="subcategory"]').value = test.subcategory;
                document.querySelector('input[name="method"]').value = test.method;
                document.querySelector('input[name="report_days"]').value = test.report_days;

                // Set charge_category_id in the select dropdown
                document.querySelector('select[name="charge_category_id"]').value = test.charge_category_id;

                document.querySelector('input[name="standard_charge"]').value = test.standard_charge;
            } else {
                // Optionally clear the fields if the checkbox is unchecked
                document.querySelector('input[name="test_name"]').value = '';
                document.querySelector('input[name="short_name"]').value = '';
                document.querySelector('input[name="test_type"]').value = '';
                document.querySelector('select[name="category_id"]').value = '';
                document.querySelector('input[name="unit"]').value = '';
                document.querySelector('input[name="subcategory"]').value = '';
                document.querySelector('input[name="method"]').value = '';
                document.querySelector('input[name="report_days"]').value = '';
                document.querySelector('select[name="charge_category_id"]').value = '';
                document.querySelector('input[name="standard_charge"]').value = '';
            }
        });
    });
});

</script>