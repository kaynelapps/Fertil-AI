<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class CalculatorTool extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia,HasSlug;

    protected $fillable = [ 'type', 'slug','name', 'description',  'blog_link', 'status' ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'blog_link','id');
    }
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug')->doNotGenerateSlugsOnUpdate();
    }
}