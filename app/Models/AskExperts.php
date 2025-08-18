<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class AskExperts extends BaseModel implements HasMedia
{
    use HasFactory,InteractsWithMedia, SoftDeletes;

    protected $fillable = [ 'user_id','title','description','expert_id','expert_answer','status' ];

    protected $casts = [
        'user_id' => 'integer',  
        'expert_id' => 'integer',  
        'status' => 'integer',  
        'title' => 'string',  
        'description' => 'string', 
        'expert_answer' => 'string',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function healthexpert()
    {
        return $this->belongsTo(HealthExpert::class, 'expert_id', 'user_id');
    }

    public function scopeGetAskexpert($q)
    {
        $auth_user = auth()->user();
        if($auth_user->user_type == 'doctor'){
            $q = $q->where('status',0);
        } elseif ($auth_user->user_type == 'app_user' || $auth_user->user_type == 'anonymous_user'){
            $q = $q->where('user_id',$auth_user->id);
        }
        return $q;
    }
}
