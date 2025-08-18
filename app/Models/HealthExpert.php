<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthExpert extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    use SoftDeletes;

    protected $fillable = [ 'user_id', 'tag_line','short_description','career','education','awards_achievements','area_expertise','is_access','experience','gender'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function healthExpertSession()
    {
        return $this->belongsTo(HealthExpertSession::class, 'id','health_expert_id');
    }

    public function article()
    {
        return $this->hasMany(Article::class,'expert_id','id');
    }

    public function review()
    {
        return $this->hasMany(Review::class,'health_expert_id','id');
    }

    public function scopeActive($query)
    {
        return $query->whereHas('users', function ($q) {
            $q->where('status', 'active');
        })->whereHas('healthExpertSession', function ($q) {
            $q->whereColumn('health_expert_id', 'health_experts.id');
        });
    }

    protected static function boot()
    {
        parent::boot();
        static::deleted(function ($row) {
            $row->article()->delete();
            $row->healthExpertSession()->forceDelete();
        });
    }
}
