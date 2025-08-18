<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CycleDateData extends BaseModel implements HasMedia
{
    use HasFactory, SoftDeletes , InteractsWithMedia;

    protected $fillable = [ 'slide_type','title','message','question','answer','cycle_date_id','status','article_id' ];

    protected $casts = [
        'article_id' => 'integer',
    ];

    public function cycle_date()
    {
        return $this->belongsTo(CycleDates::class, 'cycle_date_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
