<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthExpertSession extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [ 'health_expert_id','week_days', 'morning_start_time', 'morning_end_time', 'evening_start_time','evening_end_time' ];

    protected $casts = [
        'health_expert_id' => 'integer',
    ];
    
    public function getWeekDaysAttribute($value)
    {
        return isset($value) ? json_decode($value, true) : null; 
    }

    public function setWeekDaysAttribute($value)
    {
        $this->attributes['week_days'] = isset($value) ? json_encode($value) : null;
    }

    public function health_expert()
    {
        return $this->belongsTo(HealthExpert::class,'health_expert_id','id');
    }

    public function scopeGetHealthExpertSession($query)
    {
        $auth_user = auth()->user();
        if ($auth_user->hasRole('doctor')) {
            $query = $query->wherehas('health_expert',function($q) use($auth_user){
                $q->where('user_id',$auth_user->id);
            });
        }
        return $query;    
    }
}
