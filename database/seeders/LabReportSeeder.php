<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabVisit;
use App\Models\LabTestResult;
use Carbon\Carbon;

class LabReportSeeder extends Seeder
{
    // database/seeders/LabReportSeeder.php

public function run()
{
    // Clear old data (optional)
    \App\Models\LabTestResult::truncate();
    \App\Models\LabVisit::truncate();

    $companies = [
        'CNML' => 28,
        'GOLDEN DYNASTY' => 26,
        'CSC' => 13,
        'ATS' => 11,
        'E & P' => 7,
        'HONJOE' => 3,
        'ENFI' => 3,
    ];

    $testData = [
        'FULL BLOOD COUNT (FBC)' => [0, 0, 108],
        'MALARIA TEST' => [107, 1, 108],
        'TYPHOID TEST' => [88, 30, 118],
        'H. PYLORI' => [48, 20, 68],
        'BLOOD GLUCOSE' => [0, 0, 21],
        'URINE R/E' => [0, 0, 10],
        'BLOOD GROUP' => [0, 0, 6],
        'UPT' => [5, 0, 5],
        'STOOL R/E' => [0, 0, 1],
        'HEPATITIS B TEST' => [1, 0, 1],
        'HEPATITIS C TEST' => [1, 0, 1],
    ];

    $date = Carbon::create(2025, 4, 15);

    foreach ($companies as $name => $count) {
        $company = \App\Models\Company::firstOrCreate(['name' => $name]);

        for ($i = 0; $i < $count; $i++) {
            $visit = LabVisit::create([
                'patient_id' => 1,
                'company_id' => $company->id,
                'visit_date' => $date->copy()->addDays(rand(-10, 10)),
            ]);

            foreach ($testData as $testName => [$neg, $pos, $total]) {
                if ($total > 0) {
                    $result = null;
                    if (rand(1, $total) <= $neg + $pos) {
                        $result = rand(1, $neg + $pos) <= $neg ? 'negative' : 'positive';
                    }
                    LabTestResult::create([
                        'lab_visit_id' => $visit->id,
                        'test_name' => $testName,
                        'result' => $result,
                    ]);
                }
            }
        }
    }
}
}
