<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tags;

class TagsSeeder extends Seeder
{
    public function run()
    {
        $tags = [
            // Menstrual Health Tags
            ['name' => 'Period Tracking', 'status' => 1],
            ['name' => 'Menstrual Cycle', 'status' => 1],
            ['name' => 'PMS', 'status' => 1],
            ['name' => 'Irregular Periods', 'status' => 1],
            
            // Fertility Tags
            ['name' => 'Ovulation', 'status' => 1],
            ['name' => 'Fertility Awareness', 'status' => 1],
            ['name' => 'Conception Tips', 'status' => 1],
            ['name' => 'Fertility Treatments', 'status' => 1],
            
            // Pregnancy Tags
            ['name' => 'First Trimester', 'status' => 1],
            ['name' => 'Second Trimester', 'status' => 1],
            ['name' => 'Third Trimester', 'status' => 1],
            ['name' => 'Pregnancy Symptoms', 'status' => 1],
            
            // Menopause Tags
            ['name' => 'Perimenopause', 'status' => 1],
            ['name' => 'Menopause Symptoms', 'status' => 1],
            ['name' => 'Hormone Changes', 'status' => 1],
            ['name' => 'Post-Menopause', 'status' => 1],
            
            // Health & Wellness Tags
            ['name' => 'Nutrition', 'status' => 1],
            ['name' => 'Exercise', 'status' => 1],
            ['name' => 'Mental Health', 'status' => 1],
            ['name' => 'Self-Care', 'status' => 1],
            
            // Medical Tags
            ['name' => 'Medical Conditions', 'status' => 1],
            ['name' => 'Treatments', 'status' => 1],
            ['name' => 'Medications', 'status' => 1],
            ['name' => 'Healthcare', 'status' => 1],
        ];

        foreach ($tags as $tag) {
            Tags::create($tag);
        }
    }
}
