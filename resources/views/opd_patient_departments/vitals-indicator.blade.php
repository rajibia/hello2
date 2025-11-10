<div class="vitals-indicator">
    @if($patientsVitals && !is_null($patientsVitals->bp))
        @php
            $bpValues = explode('/', $patientsVitals->bp);
            $systolic = (int) $bpValues[0];
            $diastolic = count($bpValues) > 1 ? (int) $bpValues[1] : null;

            // Determine blood pressure status based on the reference image
            if ($systolic < 90 || $diastolic < 60) {
                $bgColor = '#ADD8E6';  // Light blue for hypotension
                $color = '#000000';
                $status = 'Hypotension';
            } elseif ($systolic >= 90 && $systolic <= 119 && $diastolic >= 60 && $diastolic <= 79) {
                $bgColor = '#28a745';  // Green for normal
                $color = '#FFFFFF';
                $status = 'Normal blood pressure';
            } elseif ($systolic >= 120 && $systolic <= 129 && $diastolic >= 60 && $diastolic <= 79) {
                $bgColor = '#FFD700';  // Gold for elevated
                $color = '#000000';
                $status = 'Elevated blood pressure';
            } elseif (($systolic >= 130 && $systolic <= 139) || ($diastolic >= 80 && $diastolic <= 89)) {
                $bgColor = '#FFA500';  // Orange for stage 1 hypertension
                $color = '#000000';
                $status = 'Hypertension - Stage 1';
            } elseif (($systolic >= 140 && $systolic <= 180) || ($diastolic >= 90 && $diastolic <= 120)) {
                $bgColor = '#FF4C4C';  // Red for stage 2 hypertension
                $color = '#FFFFFF';
                $status = 'Hypertension - Stage 2';
            } elseif ($systolic > 180 || $diastolic > 120) {
                $bgColor = '#8B0000';  // Dark red for hypertensive crisis
                $color = '#FFFFFF';
                $status = 'Hypertensive crisis. Consult your doctor immediately.';
            } else {
                // Fallback for any unexpected values
                $bgColor = '#28a745';
                $color = '#FFFFFF';
                $status = 'Normal blood pressure';
            }

            $labels = [];
            $systolicData = [];
            $diastolicData = [];

            foreach ($patientsAllVitals as $vital) {
                $labels[] = $vital->created_at->format('d M Y H:i');
            
                if (strpos($vital->bp, '/') !== false) {
                    [$sys, $dia] = explode('/', $vital->bp);
                    $systolicData[] = (int) trim($sys);
                    $diastolicData[] = (int) trim($dia);
                } else {
                    // Fallback in case the value is not properly formatted
                    $systolicData[] = $vital->bp;
                    $diastolicData[] = 0;
                }
            }
        @endphp

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <div class="card mb-4 shadow-sm border-0" style="background-color: {{ $bgColor }};">
            <div class="card-header text-white fw-bold" style="background-color: rgba(0,0,0,0.2);">
                Blood Pressure Indicator
            </div>
            <div class="card-body" style="color: {{ $color }};">
                <h4 class="card-title mb-2">
                    Systolic: {{ $systolic }} mmHg / Diastolic: {{ $diastolic }} mmHg
                </h4>
                <p class="card-text">{{ $status }}</p>
                
                <div class="text-center mt-4 mb-2">
                    <h6 class="text-uppercase" style="color: {{ $color }};">BP History</h6>
                </div>

                <div style="height: 200px; width: 100%;">
                    <canvas id="bpChart" height="100"></canvas>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info" role="alert">
            No vitals available for this patient.
        </div>
    @endif
</div>
<script>
    // Use an immediately invoked function expression (IIFE) to isolate variables
    (function() {
        // Check if the chart element exists before trying to initialize
        const chartElement = document.getElementById('bpChart');
        if (!chartElement) return;
        
        const ctx = chartElement.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [
                    {
                        label: 'Systolic (mmHg)',
                        data: @json($systolicData),
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                        pointBackgroundColor: '#007bff',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Diastolic (mmHg)',
                        data: @json($diastolicData),
                        borderColor: '#20c997',
                        backgroundColor: 'rgba(32, 201, 151, 0.2)',
                        pointBackgroundColor: '#20c997',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'mmHg',
                            color: '{{ $color }}'
                        },
                        ticks: {
                            color: '{{ $color }}'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Time',
                            color: '{{ $color }}'
                        },
                        ticks: {
                            color: '{{ $color }}'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '{{ $color }}'
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                }
            }
        });
    })();
</script>