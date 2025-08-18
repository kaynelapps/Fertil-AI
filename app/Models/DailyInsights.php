<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


class DailyInsights extends BaseModel
{
    use SoftDeletes,HasSlug;

    protected $fillable = ['title', 'goal_type', 'phase', 'symptoms_id', 'sub_symptoms_id', 'status'];

    public function symptom()
    {
        return $this->belongsTo(Symptoms::class, 'symptoms_id');
    }

    public function subSymptoms()
    {
        return $this->belongsTo(SubSymptoms::class, 'sub_symptoms_id');
    }
    
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug')->doNotGenerateSlugsOnUpdate();
    }
}
