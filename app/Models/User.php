<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name','last_name','email','password','display_name','timezone','email_verified_at','user_type','goal_type','period_start_date','cycle_length','period_length','luteal_phase', 'status', 'last_notification_seen', 'player_id','app_version','app_source','last_actived_at','otp','conversion_date','age','revenuecat_app_id','is_subscription'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'period_start_date' => 'datetime',
        'cycle_length'      => 'integer',
        'period_length'     => 'integer',
        'luteal_phase'      => 'integer',
        'is_subscription'   => 'boolean', 
    ];

    public function scopeAdmin($query) {
        return $query->where('user_type', 'admin')->first();
    }

    public function routeNotificationForOneSignal()
    {
        return $this->player_id;
    }

    public function user_symptom(){
        return $this->hasMany(UserSymptom::class,'user_id','id');
    }

    public function bookmark_insights(){
        return $this->hasMany(BookmarkInsights::class,'user_id','id');
    }
    public function bookmark_articles(){
        return $this->hasMany(BookmarkActicle::class,'user_id','id');
    }

    public function log_period(){
        return $this->hasMany(LogPeriod::class,'user_id','id');
    }

    public function session_registration(){
        return $this->hasMany(SessionRegistration::class,'user_id','id');
    }

    public function health_expert(){
        return $this->belongsTo(HealthExpert::class,'id','user_id');
    }

    public function scopeGetUser($query, $user_type=null)
    {
        $auth_user = auth()->user();

        if( $auth_user->hasAnyRole(['admin']) ) {
            $query->where('user_type', $user_type)->where('status','active');
            return $query;
        }
    }
    protected static function boot()
    {
        parent::boot();
        static::deleted(function ($row) {
            if ($row->user_type == 'doctor') {
                $row->health_expert()->delete();
            }else{
                $row->log_period()->delete();
                $row->bookmark_insights()->delete();
                $row->session_registration()->forceDelete();
                $row->user_symptom()->delete();
            }
        });
    }

    protected static function booted()
    {
        static::updated(function ($model) {
            if (!config('settings.activity_log_enabled') || !Schema::hasTable('activity_log')) return;
            $user = auth()->user();
            $userName = $user->display_name ?? 'System';
            $exclude = $model->excludeFromLog ?? ['updated_at', 'created_at', 'otp', 'last_actived_at', 'last_notification_seen', 'password'];

            $changes = collect($model->getChanges())->except($exclude);
            if ($changes->isEmpty()) return;

            // Format readable field names
            $formatKey = fn($key) => ucfirst(str_replace('_', ' ', preg_replace('/_id$/', ' ID', $key)));

            $formattedChanges = $changes->map(function ($new, $key) use ($model) {
                $old = $model->getOriginal($key);
                return [
                    'from'  => $old === '' || $old === null ? '(empty)' : $old,
                    'to'    => $new === '' || $new === null ? '(empty)' : $new,
                ];
            });

            // Human-readable message for the log field
            $logChanges = $formattedChanges->map(function ($change, $key) use ($formatKey) {
                return $formatKey($key) . ' changed from "' . $change['from'] . '" to "' . $change['to'] . '"';
            })->implode(', ');

            // Build formatted properties with pretty keys
            $properties = [
                'attributes'    => $formattedChanges->mapWithKeys(fn($v, $k) => [$formatKey($k) => $v['to']]),
                'old'           => $formattedChanges->mapWithKeys(fn($v, $k) => [$formatKey($k) => $v['from']]),
                'ip'            => request()->ip(),
            ];

            activity(class_basename($model))
                ->causedBy($user)
                ->performedOn($model)
                ->withProperties($properties)
                ->log("{$userName} updated " . class_basename($model) . " #{$model->id} – $logChanges");
        });


        static::created(function ($model) {
            if (!config('settings.activity_log_enabled') || !Schema::hasTable('activity_log')) return;
            $user = auth()->user();
            $userName = $user->display_name ?? 'System';
            activity(class_basename($model))
                ->causedBy($user)
                ->performedOn($model)
                ->withProperties([
                    'ip' => request()->ip(),
                ])
                ->log("{$userName} added " . class_basename($model) . " #{$model->id}");
        });


        static::deleted(function ($model) {
            if (!config('settings.activity_log_enabled') || !Schema::hasTable('activity_log')) return;
            $user = auth()->user();
            $userName = $user->display_name ?? 'System';
            activity(class_basename($model))
                ->causedBy($user)
                ->performedOn($model)
                ->withProperties([
                    'ip' => request()->ip(),
                ])
                ->log("{$userName} deleted " . class_basename($model) . " #{$model->id}");
        });
    }
}
