<?php

namespace App\Services;

use App\Models\IdSetting;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PatientIdGenerator
{
    public function next(): string
    {
        return DB::transaction(function () {
            // Lock the row for update to avoid race conditions
            $setting = IdSetting::where('scope', 'patient')->lockForUpdate()->first();

            if (!$setting || !$setting->enabled) {
                throw new RuntimeException('Patient ID auto-generation is disabled or not configured.');
            }

            $setting->current_counter += 1;
            $setting->save();

            $number = str_pad((string)$setting->current_counter, $setting->digits, '0', STR_PAD_LEFT);
            return $setting->prefix.$number;
        }, 3); // retry up to 3 times
    }
}
