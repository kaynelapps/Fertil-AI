<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Article extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia , SoftDeletes;
    use HasSlug;
    
    protected $fillable = [ 'name', 'slug','tags_id', 'expert_id','goal_type','description', 'status' ,'article_type','type','weeks','sub_symptoms_id'];

    public function getTagsIdAttribute($value)
    {
        return isset($value) ? json_decode($value, true) : null; 
    }

    public function setTagsIdAttribute($value)
    {
        $this->attributes['tags_id'] = isset($value) ? json_encode($value) : null;
    }

    public function insights()
    {
        return $this->hasMany(Insights::class, 'article_id');
    }

    public function health_experts()
    {
        return $this->belongsTo(HealthExpert::class, 'expert_id');
    }

    public function article_reference()
    {
        return $this->hasMany(ArticleReference::class, 'article_id');
    }

    public function pregnancy_date()
    {
        return $this->hasMany(PregnancyDate::class, 'article_id');
    }

    public function scopeGetTags($query)
    {
        if ($tagId = request('tag_id')) {
            $query->whereJsonContains('tags_id', $tagId);
        }

        return $query;
    }
    public function bookmark()
    {
        if (auth()->check()) {
            return $this->hasOne(BookmarkActicle::class, 'article_id')
                        ->where('user_id', auth()->id());
        }
        return $this->hasOne(BookmarkActicle::class, 'article_id')
                    ->whereRaw('1 = 0'); // Always false condition
    }

    public function getIsBookmarkAttribute()
    {
        return optional($this->bookmark)->is_bookmark ?? 0;
    }
    
    

    public function scopegetArticle($query)
    {
        $auth_user = auth()->user();
        if ($auth_user->user_type == 'doctor') {
            # code...
            $query = $query->where('expert_id',optional($auth_user->health_expert)->id);
        }else{
            if (!$auth_user->user_type == 'admin') {
                # code...
                $query = $query->where('goal_type',$auth_user->goal_type);
            }
        }

        return $query;
    }

    protected static function boot()
    {
        parent::boot();
        static::deleted(function ($row) {
            $row->article_reference()->delete();
            $row->pregnancy_date()->forceDelete();
        });
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug')->doNotGenerateSlugsOnUpdate();
    }
}
