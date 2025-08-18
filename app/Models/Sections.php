<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Sections extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia,HasSlug;
    use SoftDeletes;

    protected $fillable = [ 'title','goal_type','category_id', 'description','status'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
     public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug')->doNotGenerateSlugsOnUpdate();
    }
}
