<div class="vitals-indicator">
    @if($vitals && !is_null($vitals->bp))
        @php
    // Split the BP value into systolic and diastolic if available
    $bpValues = explode('/', $vitals->bp);
    // dd(count($bpValues));
    $systolic = (int)$bpValues[0];
    $diastolic = count($bpValues) > 1 ? (int)$bpValues[1] : "";

    // Determine the background color based on the systolic and diastolic values
    if ($systolic > 180 || $diastolic > 120) {
        $bgColor = '#d00032'; // Hypertensive Crisis
        $color = '#e2d5d8';
        $status = 'Hypertensive crisis. Consult your doctor immediately.';
    } elseif ($systolic >= 140 || $diastolic >= 90) {
        $bgColor = '#df5017'; // Hypertension Stage 2
        $color = '#fff';
        $status = 'High blood pressure (Hypertension Stage 2). Consult your doctor.';
    } elseif (($systolic >= 130 && $systolic <= 139) || ($diastolic >= 80 && $diastolic <= 89)) {
        $bgColor = '#ec9612'; // Hypertension Stage 1
        $color = '#fff';
        $status = 'High blood pressure (Hypertension Stage 1). Consult your doctor.';
    } elseif ($systolic >= 120 && $systolic <= 129 && $diastolic < 80) {
        $bgColor = '#d3e210'; // Elevated
        $color = '#fff';
        $status = 'Elevated blood pressure. Monitor your blood pressure.';
    } else {
        $bgColor = '#a6e210'; // Normal
        $color = '#000';
        $status = 'Normal blood pressure.';
    }
@endphp


        <div class="card mb-3 d-flex justify-content-center" style="background-color: {{ $bgColor }};">
            <div class="card-header">Blood Pressure Indicator</div>
            <div class="card-body" style="color: {{ $color }};">
                <h4 class="card-title">
                    Systolic: {{ $systolic }} mmHg / Diastolic: {{ $diastolic }} mmHg
                </h4>
                <p class="card-text">
                    {{ $status }}
                </p>
            </div>
        </div>
    @else
        <div class="alert alert-info" role="alert">
            No vitals available for this patient.
        </div>
    @endif
</div>
