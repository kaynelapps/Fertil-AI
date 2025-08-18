<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Symptoms extends BaseModel
{
    use HasFactory, HasSlug;
    use SoftDeletes;

    protected $fillable = [ 'title','slug', 'bg_color', 'article_id','status' ];

    public function subSymptoms()
    {
        return $this->hasMany(SubSymptoms::class, 'symptoms_id');
    }

    public function scopeSymptomsList($query)
    {
        if (request('id') != null) {
            $query = $query->where(['id' => request('id')]);
        } 

        return $query;
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug')->doNotGenerateSlugsOnUpdate();
    }
}
