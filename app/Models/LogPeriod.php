<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPeriod extends BaseModel
{
    use HasFactory;

    protected $fillable = ['user_id','period_date'];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
