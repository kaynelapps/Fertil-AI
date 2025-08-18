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
            ['name' => 'Period Tracking'],
            ['name' => 'Menstrual Cycle'],
            ['name' => 'PMS'],
            ['name' => 'Irregular Periods'],
            
            // Fertility Tags
            ['name' => 'Ovulation'],
            ['name' => 'Fertility Awareness'],
            ['name' => 'Conception Tips'],
            ['name' => 'Fertility Treatments'],
            
            // Pregnancy Tags
            ['name' => 'First Trimester'],
            ['name' => 'Second Trimester'],
            ['name' => 'Third Trimester'],
            ['name' => 'Pregnancy Symptoms'],
            
            // Menopause Tags
            ['name' => 'Perimenopause'],
            ['name' => 'Menopause Symptoms'],
            ['name' => 'Hormone Changes'],
            ['name' => 'Post-Menopause'],
            
            // Health & Wellness Tags
            ['name' => 'Nutrition'],
            ['name' => 'Exercise'],
            ['name' => 'Mental Health'],
            ['name' => 'Self-Care'],
            
            // Medical Tags
            ['name' => 'Medical Conditions'],
            ['name' => 'Treatments'],
            ['name' => 'Medications'],
            ['name' => 'Healthcare'],
        ];

        foreach ($tags as $tag) {
            Tags::create($tag);
        }
    }
}
