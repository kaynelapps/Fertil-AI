<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ModelHasRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        // Clean up existing role assignments
        if (DB::connection()->getDriverName() === 'pgsql') {
            // For PostgreSQL
            DB::statement('TRUNCATE model_has_roles CASCADE');
        } else {
            // For MySQL or other databases
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('model_has_roles')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
        
        DB::table('model_has_roles')->insert(array (
            0 => 
            array (
                'model_id' => 1,
                'model_type' => 'App\\Models\\User',
                'role_id' => 1,
            ),
            1 => 
            array (
                'model_id' => 2,
                'model_type' => 'App\\Models\\User',
                'role_id' => 5,
            )
        ));
    }
}