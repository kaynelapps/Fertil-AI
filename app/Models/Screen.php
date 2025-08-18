<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screen extends BaseModel
{
    use HasFactory;

    protected $fillable = ['screen_id', 'screen_name'];

}
