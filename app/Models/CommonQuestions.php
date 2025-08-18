<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommonQuestions extends BaseModel
{
    use HasFactory , SoftDeletes;
    

    protected $fillable = ['question','answer', 'goal_type', 'category_id','article_id','status' ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
