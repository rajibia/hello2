@if($users->isNotEmpty())
    <div class="search-results mt-4">
        <h4>Search Results</h4>
        <table class="table table-striped">
            <thead class="bg-warning text-black">
                <tr>
                    <th>Select</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Insurance Number</th>
                    <th>Email</th>
                    <th>Location</th>
                    <th>Department</th>
                    <th>Case Number</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @php
                        // Get patient ID and related data
                        $patient = \App\Models\Patient::where('user_id', $user->id)->first();
                        $patientId = $patient?->id;

                        $opdRecord = $opdPatientDepartments->firstWhere('patient_id', $patientId);
                        $opdPatientCase = $opdPatientCases->firstWhere('patient_id', $patientId);
                    @endphp
                    <tr>
                        <td>
                            @if($patientId)
                                <input type="radio" name="selected_patient" 
                                    value="{{ $patientId }}" 
                                    class="form-check-input"
                                    onchange="selectPatient(
                                        '{{ $patientId }}',
                                        '{{ $user->first_name }} {{ $user->last_name }}',
                                        '{{ $opdRecord?->opd_number ?? '' }}',
                                        '{{ $opdPatientCase?->case_id ?? '' }}',
                                        '{{ $opdRecord?->height ?? 0 }}',
                                        '{{ $opdRecord?->weight ?? 0 }}',
                                        '{{ $opdRecord?->bp ?? '' }}',
                                        '{{ $opdRecord?->temperature ?? '' }}',
                                        '{{ $opdRecord?->respiration ?? '' }}'
                                    )">
                            @endif
                        </td>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->insurance_number }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->location }}</td>
                        <td>{{ $user->department?->name ?? 'N/A' }}</td>
                        <td>{{ $opdPatientCase?->case_id ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <!--<p>No results found for your search.</p>-->
@endif

<script>
function selectPatient(patientId, patientName, opdNumber, caseId, height, weight, bp, temperature, respiration) {
    $('#hiddenPatientId').val(patientId);
    $('#patientSelect').val(patientId).trigger('change');
    $('#CaseId').prop('disabled', false).val(caseId).trigger('change');
    $('#height').val(height);
    $('#weight').val(weight);
    $('#bp').val(bp);
    $('#temperature').val(temperature);
    $('#respiration').val(respiration);
    
    // Check if this is an old patient (if they have been through the system before)
    // If patientId exists, it means they're already in the system, so check the box
    if (patientId) {
        $('#is_old_patient').prop('checked', true);
    }
}
</script>
