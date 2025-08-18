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
        // Delete existing role assignments for our users
        DB::table('model_has_roles')
            ->whereIn('model_id', [1])
            ->where('model_type', 'App\\Models\\User')
            ->delete();
        
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