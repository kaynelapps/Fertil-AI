<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleReference extends BaseModel
{
    use HasFactory;

    protected $fillable = [ 'article_id','reference' ];

    protected $casts = [
        'article_id' => 'integer',
    ];

    public function article(){
        return $this->belongsTo(Article::class,'article_id','id');
    }
}
