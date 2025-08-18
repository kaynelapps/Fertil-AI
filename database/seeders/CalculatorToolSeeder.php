<?php

namespace Database\Seeders;

use App\Models\CalculatorTool;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CalculatorToolSeeder extends Seeder
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
                'type' => 'ovulation_calculator',
                'name' => 'Ovulation calculator',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 2,
                'type' => 'pregnancy_test_calculator',
                'name' => 'Pregnancy test calculator',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 3,
                'type' => 'period_calculator',
                'name' => 'Period calculator',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 4,
                'type' => 'implantation_calculator',
                'name' => 'Implantation calculator',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
            [
                'id' => 5,
                'type' => 'pregnancy_due_date_calculator',
                'name' => 'Pregnancy due date calculator',
                'description' => NULL,
                'blog_link' => NULL,
                'status' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ],
        ];

        foreach ($data as $value) {
            if (!CalculatorTool::where('type', $value['type'])->exists()) {
                CalculatorTool::create($value);
            }
        }
    }
}
