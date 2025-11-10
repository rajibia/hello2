<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IdSetting;

use Flash;

class IdSettingController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth'); // enable if needed
        $this->ensureDefaultPatientRow();
    }

    /**
     * Optional standalone view (not required for your appended form)
     */
    public function edit(string $scope = 'patient')
    {
        $setting = IdSetting::where('scope', $scope)->first();

        // If someone hits a different scope directly, ensure it exists with defaults
        if (!$setting) {
            $setting = $this->createWithDefaults($scope);
        }

        return view('settings.id_settings_only', [
            'scope'   => $scope,
            'setting' => $setting,
            'preview' => $this->previewNextId($setting),
        ]);
    }

    /**
     * Create/Update (idempotent) for the given scope
     */
    public function upsert(Request $request, string $scope = 'patient')
    {
        $validated = $request->validate([
            'enabled'         => ['required', 'boolean'],
            'prefix'          => ['required', 'string', 'max:20'],
            'digits'          => ['required', 'integer', 'min:1', 'max:12'],
            'current_counter' => ['required', 'integer', 'min:0'],
        ]);

        $setting = IdSetting::firstOrNew(['scope' => $scope]);
        $setting->enabled         = (bool) $validated['enabled'];
        $setting->prefix          = $validated['prefix'];
        $setting->digits          = (int) $validated['digits'];
        $setting->current_counter = (int) $validated['current_counter'];
        $setting->save();

        if ($setting->enabled) {
            \DB::transaction(function () use ($setting) {
                $prefix = $setting->prefix;
                $digits = (int) $setting->digits;
                $counter = (int) $setting->current_counter;    // start from here
                $max = (int) str_repeat('9', $digits);         // e.g. 5 -> 99999

                // Phase 0 (optional but nice): sanity check capacity
                $total = \DB::table('patients')->count();
                if ($counter + $total > $max) {
                    throw new \RuntimeException(
                        "Not enough ID capacity for {$digits} digits. ".
                        "Need ".($counter + $total)." up to {$max}."
                    );
                }

                // Phase 1: move all IDs to a guaranteed-unique temp value to avoid unique index collisions
                // Use chunking for memory safety
                \DB::table('patients')->orderBy('id')->select('id')->chunkById(1000, function ($rows) {
                    foreach ($rows as $row) {
                        \DB::table('patients')
                            ->where('id', $row->id)
                            ->update([
                                'patient_unique_id' => \DB::raw("CONCAT('__TMP__', id)")
                            ]);
                    }
                });

                // Phase 2: assign final IDs using saved settings (prefix + zero-padded number)
                \DB::table('patients')->orderBy('id')->select('id')->chunkById(1000, function ($rows) use (&$counter, $prefix, $digits) {
                    foreach ($rows as $row) {
                        $counter++;
                        $num = str_pad((string) $counter, $digits, '0', STR_PAD_LEFT);
                        $final = $prefix.$num;

                        \DB::table('patients')
                            ->where('id', $row->id)
                            ->update(['patient_unique_id' => $final]);
                    }
                });

                // Persist the new counter so future IDs continue correctly
                $setting->current_counter = $counter;
                $setting->save();
            });
        }

        Flash::success(__('messages.settings').' '.__('messages.common.updated_successfully'));

        return back()->with('status', 'Patient ID settings saved and all IDs rebuilt with prefix and zero padding.');
    }



    /* ----------------------- helpers ----------------------- */

    private function ensureDefaultPatientRow(): void
    {
        if (!IdSetting::where('scope', 'patient')->exists()) {
            $this->createWithDefaults('patient');
        }
    }

    private function createWithDefaults(string $scope): IdSetting
    {
        return IdSetting::create([
            'scope'            => $scope,
            'enabled'          => true,
            'prefix'           => 'PT-',
            'digits'           => 5,
            'current_counter'  => 0,
        ]);
    }

    private function previewNextId(IdSetting $setting): ?string
    {
        if (!$setting->enabled) {
            return null;
        }
        $next = $setting->current_counter + 1;
        $padded = str_pad((string) $next, $setting->digits, '0', STR_PAD_LEFT);
        return $setting->prefix . $padded;
    }
}
