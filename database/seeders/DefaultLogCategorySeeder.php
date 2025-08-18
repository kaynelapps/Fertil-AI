<?php

namespace Database\Seeders;

use App\Models\DefaultLogCategory;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DefaultLogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('calculator_tools')->truncate();

        $data = [
            [
                'id' => 1,
                'type' => 'meditation',
                'name' => 'Meditation',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 2,
                'type' => 'sleep',
                'name' => 'Sleep',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 3,
                'type' => 'water',
                'name' => 'Water',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 4,
                'type' => 'notes',
                'name' => 'Notes',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 5,
                'type' => 'weight',
                'name' => 'Weight',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 6,
                'type' => 'temparature',
                'name' => 'Temparature',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
        ];

        foreach ($data as $value) {
            if (!DefaultLogCategory::where('type', $value['type'])->exists()) {
                DefaultLogCategory::create($value);
            }
        }
    }
}
