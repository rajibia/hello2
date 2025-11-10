<?php

namespace App\Imports;

use App\Models\DiagnosisCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DiagnosisCategoriesImport implements ToModel, WithHeadingRow
{
    /**
     * Map each row of the Excel file to a model or database.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            // Debugging: Log the incoming row
            \Log::info('Processing row: ', $row);

            // Check if a record with the same code already exists
            $existingCategory = DiagnosisCategory::where('code', $row['codes'])->first();

            if ($existingCategory) {
                // Update the existing record
                $existingCategory->update([
                    'name' => $row['diagnosis'],
                ]);
                return $existingCategory;
            }

            // Create a new record if no match is found
            return new DiagnosisCategory([
                'code' => $row['codes'],
                'name' => $row['diagnosis'],
            ]);
        } catch (\Throwable $e) {
            // Log the error for this specific row
            \Log::error('Row Processing Error: ' . $e->getMessage());
        }
    }
}
