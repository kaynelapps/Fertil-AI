<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class DefaultLogCategory extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [ 'type', 'name', 'description',  'blog_link', 'status' ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'blog_link','id');
    }
}
