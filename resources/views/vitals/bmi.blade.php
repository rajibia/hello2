@php
    $height = $row->height; // assumed in feet
    $weight = $row->weight; // in kg
    $bmi = null;

    if ($height && $weight && $height > 0) {
        $heightInMeters = $height * 0.3048;
        $bmi = round($weight / ($heightInMeters * $heightInMeters), 1);
    }
@endphp

<span>
    {{ $bmi ? $bmi : 'N/A' }}
</span>
