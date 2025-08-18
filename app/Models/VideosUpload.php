<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideosUpload extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia , SoftDeletes;

    protected $fillable = [ 'title','video_duration','status' ];
}
