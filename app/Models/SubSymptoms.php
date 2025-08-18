<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubSymptoms extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [ 'title', 'article_id','description', 'symptoms_id','status' ];

    public function symptom()
    {
        return $this->belongsTo(Symptoms::class, 'symptoms_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function insights()
    {
        return $this->hasMany(Insights::class, 'sub_symptoms_id');
    }
}
