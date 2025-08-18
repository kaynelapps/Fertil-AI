<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // First truncate all tables in reverse order
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('TRUNCATE TABLE users, roles, permissions, model_has_roles, role_has_permissions, model_has_permissions CASCADE');
        }
        
        $this->call([
            RoleTableSeeder::class,
            PermissionTableSeeder::class,
            UserTableSeeder::class,
            RoleHasPermissionsTableSeeder::class,
            ModelHasRolesTableSeeder::class,
            ModelHasPermissionsTableSeeder::class,
            AppSettingTableSeeder::class,
            CalculatorToolSeeder::class,
            DefaultLogCategorySeeder::class,
            ScreenkeywordSeeder::class,
            LanguageDefaultListSeeder::class
        ]);
    }
}
