<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia , SoftDeletes;
    use HasSlug;

    protected $fillable = [ 'name', 'slug','goal_type','description','status' ];

    public function section_data_main()
    {
        return $this->hasMany(SectionDataMain::class, 'category_id');
    }

    public function image_section()
    {
        return $this->hasMany(ImageSection::class, 'category_id','id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleted(function ($row) {
            $row->image_section()->forceDelete();
        });
    }

     public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug')->doNotGenerateSlugsOnUpdate();
    }
}