<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class SectionDataMain extends BaseModel
{
    use HasFactory, SoftDeletes,HasSlug;

    protected $fillable = [ 'title', 'goal_type', 'category_id','is_show_insights','status','dragondrop'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function section_data()
    {
        return $this->hasMany(SectionData::class, 'main_title_id');
    }
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug')->doNotGenerateSlugsOnUpdate();
    }
}
