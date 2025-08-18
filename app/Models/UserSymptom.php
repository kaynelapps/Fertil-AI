<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSymptom extends BaseModel
{
    use HasFactory;

    protected $fillable = [ 'user_id','current_date','user_data' ];

    public function users(){
        return $this->belongsTo(user::class,'user_id','id');
    }

    public function scopeGetUserSymptomsByDate($query)
    {
        if (request('date') != null) {
            $query = $query->where(['current_date' => request('date')]);
        } 

        return $query;
    }
}
