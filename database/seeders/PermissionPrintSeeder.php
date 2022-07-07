<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionPrintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permission row for pdf 
        DB::table('permissions')->insert([
            'name' => 'surveyPrint'
        ]);
        // Permission row for excel export
        DB::table('permissions')->insert([
            'name' => 'surveyExport'
        ]);
    }
}
