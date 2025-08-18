<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class subscriptions extends BaseModel
{
    use HasFactory;
    protected $fillable = ['user_id','expires_date','product_identifier','purchase_date','subscription_id','amount','currency','store','store_transaction_id','original_app_user_id','original_application_version','original_purchase_date','full_responce','request_date'];

     public function users()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
}
