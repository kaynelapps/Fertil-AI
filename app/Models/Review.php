<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends BaseModel
{
    use HasFactory;

    protected $fillable = ['user_id', 'health_expert_id', 'review'];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function health_expert()
    {
        return $this->belongsTo(HealthExpert::class,'health_expert_id','id');
    }

    public function scopeGetReview($query)
    {
        $auth_user = auth()->user();

        if ($auth_user->hasRole('doctor')) {
            $query = $query->where('health_expert_id',optional($auth_user->health_expert)->id);
        }else{
            if ($auth_user->hasRole('admin')) {
                $query = $query;
            }else{
                $query = $query->where('user_id',$auth_user->id);
            }
        }

        return $query;
    }
}
