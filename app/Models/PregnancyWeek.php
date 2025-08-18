<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Type\Integer;

class PregnancyWeek extends BaseModel implements HasMedia
{
    use HasFactory,InteractsWithMedia, SoftDeletes;

    protected $fillable = [ 'title', 'goal_type','view_type','article_id','category_id','video_id','status','weeks'];

    protected $casts = [
        'goal_type' => 'integer',
        'category_id' => 'integer',
        'video_id' => 'integer',
        'article_id' => 'integer',
    ];



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

}
