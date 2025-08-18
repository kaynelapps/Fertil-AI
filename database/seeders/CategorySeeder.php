<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Menstrual Health',
                'description' => 'Comprehensive information about menstrual cycles, period health, and managing menstrual symptoms. Learn about cycle tracking, menstrual hygiene, and understanding your body\'s natural rhythms.',
                'goal_type' => 0,
                'status' => 1
            ],
            [
                'name' => 'Fertility & Conception',
                'description' => 'Expert guidance on fertility awareness, conception tips, and reproductive health. Understand your fertile window, optimize your chances of conception, and learn about factors affecting fertility.',
                'goal_type' => 0,
                'status' => 1
            ],
            [
                'name' => 'Pregnancy Journey',
                'description' => 'Week-by-week pregnancy guides, fetal development information, and maternal health resources. Track your pregnancy journey, understand changes in your body, and prepare for childbirth.',
                'goal_type' => 1,
                'status' => 1
            ],
            [
                'name' => 'Menopause & Perimenopause',
                'description' => 'Understanding the transition through perimenopause and menopause. Learn about symptoms, treatment options, and maintaining quality of life during hormonal changes.',
                'goal_type' => 0,
                'status' => 1
            ],
            [
                'name' => 'Reproductive Health',
                'description' => 'Essential information about reproductive system health, common conditions, and preventive care. Stay informed about reproductive wellness and healthcare options.',
                'goal_type' => 0,
                'status' => 1
            ],
            [
                'name' => 'Sexual Health & Wellness',
                'description' => 'Comprehensive resources on sexual health, relationships, and intimacy. Learn about safe practices, communication, and maintaining sexual wellness.',
                'goal_type' => 0,
                'status' => 1
            ],
            [
                'name' => 'Mental Health & Emotional Wellbeing',
                'description' => 'Support for mental and emotional health throughout your reproductive journey. Address anxiety, mood changes, and emotional challenges related to hormonal transitions.',
                'goal_type' => 0,
                'status' => 1
            ],
            [
                'name' => 'Nutrition & Lifestyle',
                'description' => 'Evidence-based guidance on nutrition, exercise, and lifestyle choices for reproductive health. Learn about dietary needs during different life stages and healthy habits.',
                'goal_type' => 0,
                'status' => 1
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
