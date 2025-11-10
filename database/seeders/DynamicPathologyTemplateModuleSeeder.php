<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DynamicPathologyTemplateModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if the module already exists
        $existingModule = DB::table('modules')
            ->where('name', 'Dynamic Pathology Templates')
            ->first();

        if (!$existingModule) {
            DB::table('modules')->insert([
                'name' => 'Dynamic Pathology Templates',
                'is_active' => 1,
                'route' => 'dynamic.pathology.templates.index',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
