<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class PersonalisedInsights extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia,HasSlug;
    use SoftDeletes;

    protected $fillable = [ 'title', 'goal_type','view_type','url','article_id','category_id','video_id','status','insights_type','users','anonymous_user','view_all_users','view_all_anonymous_users','personalinsights_data','blog_course_article_id','blog_article_id'];

    public function users()
    {
        return $this->belongsTo(User::class, 'users');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function video() {
        return $this->belongsTo(VideosUpload::class, 'video_id');
    }
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug')->doNotGenerateSlugsOnUpdate();
    }
}
