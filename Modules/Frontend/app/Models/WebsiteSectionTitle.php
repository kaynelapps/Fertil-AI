<?php

namespace Modules\Frontend\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Frontend\Database\Factories\WebsiteSectionTitleFactory;

class WebsiteSectionTitle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['section_id', 'title'];

    public function websitesection()
    {
        return $this->belongsTo(WebsiteSection::class,'section_id');
    }
}
