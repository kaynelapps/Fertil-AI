<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CycleDates extends BaseModel implements HasMedia
{
    use HasFactory,SoftDeletes,InteractsWithMedia;

    protected $fillable = [ 'day','title','goal_type','view_type','article_id','category_id','video_id','status','blog_course_article_id','blog_article_id'];

    protected $casts = [
        'goal_type' => 'integer',
        'category_id' => 'integer',
        'video_id' => 'integer',
        'article_id' => 'integer',
    ];


    public function textmessage_data()
    {
        return $this->hasMany(CycleDateData::class, 'cycle_date_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

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

    public function video() 
    {
        return $this->belongsTo(VideosUpload::class, 'video_id');
    }
}
