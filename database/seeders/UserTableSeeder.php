<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        // First, delete any existing user with the same email
        DB::table('users')->whereIn('email', [
            'kaynelapps@gmail.com'
        ])->delete();
        
        DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                // 'username' => 'admin',
                // 'contact_number' => '+919876543210',
                // 'address' => NULL,
                'email' => 'kaynelapps@gmail.com',
                'password' => bcrypt('XclavisAnaspasse14@@'),
                'email_verified_at' => NULL,
                'user_type' => 'admin',
                'player_id' => NULL,
                'remember_token' => NULL,
                'last_notification_seen' => NULL,
                'status' => 'active',
                'timezone' => 'UTC',
                'display_name' => 'Admin',
                // 'is_subscribe' => NULL,
                'cycle_length'  => '0',
                'period_length' => '0',
                'luteal_phase'  => '0',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            1=> 
            array (
                'id' => 2,
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'kaynelapps@gmail.com',
                'password' => bcrypt('XclavisAnaspasse14@@'),
                'email_verified_at' => NULL,
                'user_type' => 'super_admin',
                'player_id' => NULL,
                'remember_token' => NULL,
                'last_notification_seen' => NULL,
                'status' => 'active',
                'timezone' => 'UTC',
                'display_name' => 'Super Admin',
                // 'is_subscribe' => NULL,
                'cycle_length'  => '0',
                'period_length' => '0',
                'luteal_phase'  => '0',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            )
        ));
        
        
    }
}