<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BookmarkInsights extends BaseModel implements HasMedia 
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['user_id', 'insight_id', 'is_bookmark'];

    protected $casts = [
        'user_id' => 'integer',
        'insight_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function insight()
    {
        return $this->belongsTo(Insights::class, 'insight_id', 'id');
    }

}
