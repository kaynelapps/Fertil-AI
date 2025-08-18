<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tags;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $menstrualHealthArticles = [
            [
                'name' => 'Understanding Your Menstrual Cycle: A Comprehensive Guide',
                'description' => '
# Understanding Your Menstrual Cycle: A Comprehensive Guide

Your menstrual cycle is more than just your period - it\'s a complex interplay of hormones that affects your entire body. This guide will help you understand each phase of your cycle and what to expect.

## The Four Phases of Your Menstrual Cycle

### 1. Menstrual Phase (Days 1-5)
- This is when your period occurs
- The uterine lining sheds
- Typical symptoms and what\'s normal
- When to be concerned about heavy bleeding

### 2. Follicular Phase (Days 6-14)
- Estrogen levels begin to rise
- Your body prepares for ovulation
- Energy levels often increase
- Best time for starting new projects

### 3. Ovulation Phase (Day 14-16)
- Peak fertility window
- Physical and emotional changes
- Tracking ovulation signs
- Understanding fertility signals

### 4. Luteal Phase (Days 17-28)
- Progesterone dominance
- Common PMS symptoms
- Natural ways to manage symptoms
- When to seek medical help

## Tips for Cycle Tracking
- How to track your cycle effectively
- Best apps and tools to use
- Important symptoms to note
- Using cycle tracking for health insights

## When to Consult a Healthcare Provider
- Irregular cycles
- Severe pain or symptoms
- Changes in cycle length
- Unusual bleeding patterns

Remember, every person\'s cycle is unique, and what\'s normal can vary. Understanding your personal patterns is key to maintaining reproductive health.',
                'goal_type' => 'menstrual_health',
                'status' => 1,
                'article_type' => 'featured',
                'type' => 'article'
            ],
            // Add more articles here
        ];

        $fertilityArticles = [
            [
                'name' => 'Optimizing Your Fertility: Natural Ways to Enhance Conception',
                'description' => '
# Optimizing Your Fertility: Natural Ways to Enhance Conception

Understanding and optimizing your fertility involves multiple factors, from timing to lifestyle choices. This comprehensive guide will help you maximize your chances of conception naturally.

## Understanding Your Fertile Window

### Identifying Your Most Fertile Days
- The science of ovulation
- Best timing for conception
- Signs of fertility to watch for
- Using ovulation prediction tools

### Tracking Fertility Signs
- Cervical mucus changes
- Basal body temperature
- Physical symptoms
- Digital tracking methods

## Lifestyle Factors That Impact Fertility

### Nutrition for Fertility
- Essential nutrients for conception
- Foods to boost fertility
- Foods to avoid
- Supplement recommendations

### Exercise and Fertility
- Best types of exercise
- Exercise intensity guidelines
- Activities to avoid
- Building a fertility-friendly routine

### Stress Management
- Impact of stress on fertility
- Relaxation techniques
- Mind-body practices
- Support systems and resources

## Preparing Your Body for Conception

### Pre-conception Health
- Medical check-ups
- Genetic screening
- Vaccination updates
- Medication reviews

### Environmental Factors
- Reducing toxin exposure
- Creating a fertility-friendly home
- Workplace considerations
- Environmental impacts on fertility

## When to Seek Professional Help
- Age-related guidelines
- Common fertility challenges
- Treatment options
- Finding the right specialist

Remember that fertility is a journey, and patience is key. Focus on creating a healthy foundation while working with healthcare providers to address any specific concerns.',
                'goal_type' => 'fertility',
                'status' => 1,
                'article_type' => 'featured',
                'type' => 'article'
            ]
        ];

        // Create articles and associate with categories and tags
        foreach ($menstrualHealthArticles as $article) {
            $category = Category::where('goal_type', 'menstrual_health')->first();
            $tags = Tags::whereIn('name', ['Menstrual Cycle', 'Period Tracking'])->pluck('id')->toArray();
            
            $newArticle = Article::create(array_merge($article, [
                'tags_id' => $tags
            ]));
        }

        foreach ($fertilityArticles as $article) {
            $category = Category::where('goal_type', 'fertility')->first();
            $tags = Tags::whereIn('name', ['Fertility Awareness', 'Ovulation'])->pluck('id')->toArray();
            
            $newArticle = Article::create(array_merge($article, [
                'tags_id' => $tags
            ]));
        }
    }
}
