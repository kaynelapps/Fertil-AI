<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PushNotification extends BaseModel implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [ 'title', 'message', 'for_user', 'goal_type' ];
    
}
