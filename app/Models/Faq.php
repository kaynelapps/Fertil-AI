<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends BaseModel
{
    use HasFactory , SoftDeletes;
    protected $fillable = ['question','answer', 'goal_type','article_id','url','status' ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function scopeGetFaq($q)
    {
        $auth_user = auth()->user();
        if ($auth_user->user_type == 'app_user' || $auth_user->user_type == 'anonymous_user') {
            $q = $q->where('goal_type',$auth_user->goal_type);
        }
        return $q;
    }
}
