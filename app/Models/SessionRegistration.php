<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SessionRegistration extends BaseModel
{
    use HasFactory,SoftDeletes;

    protected $fillable = [ 'educational_session_id','user_id','registration_date', 'is_cancelled','status'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
}
