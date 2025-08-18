<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PregnancyDate extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    use SoftDeletes;

    protected $fillable = [ 'pregnancy_date','title', 'article_id', 'status'];

    protected $casts = [
        'article_id'      => 'integer',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
