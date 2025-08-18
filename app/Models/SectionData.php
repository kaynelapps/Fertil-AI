<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class SectionData extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia,HasSlug;
    use SoftDeletes;

    protected $fillable = [ 'title', 'main_title_id','view_type','url','category_id','video_id','article_id','blog_course_article_id','blog_article_id','description','status'];

    public function getBlogCourseArticleIdAttribute($value)
    {
        return isset($value) ? json_decode($value, true) : null; 
    }

    public function setBlogCourseArticleIdAttribute($value)
    {
        $this->attributes['blog_course_article_id'] = isset($value) ? json_encode($value) : null;
    }

    public function getVideoIdAttribute($value)
    {
        return isset($value) ? json_decode($value, true) : null; 
    }

    public function setVideoIdAttribute($value)
    {
        $this->attributes['video_id'] = isset($value) ? json_encode($value) : null;
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function section_data_main()
    {
        return $this->belongsTo(SectionDataMain::class, 'main_title_id');
    }

    public function video() {
        return $this->belongsTo(VideosUpload::class, 'video_id');
    }
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug')->doNotGenerateSlugsOnUpdate();
    }
}
