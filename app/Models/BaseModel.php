<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    protected static function booted()
    {
        static::updated(function ($model) {
            if (!config('settings.activity_log_enabled') || !Schema::hasTable('activity_log')) return;

            $user = auth()->user();
            $userName = $user->display_name ?? 'System';
            $exclude = $model->excludeFromLog ?? ['updated_at', 'created_at', 'last_actived_at', 'last_notification_seen', 'password'];

            $ip = request()->ip();

            $changes = collect($model->getChanges())->except($exclude);
            if ($changes->isEmpty()) return;

            // Format readable field names
            $formatKey = fn($key) => ucfirst(str_replace('_', ' ', preg_replace('/_id$/', ' ID', $key)));

            $formattedChanges = $changes->map(function ($new, $key) use ($model) {
                $old = $model->getOriginal($key);
                return [
                    'from' => $old === '' || $old === null ? '(empty)' : $old,
                    'to'   => $new === '' || $new === null ? '(empty)' : $new,
                ];
            });

            // Human-readable message for the log field
            $logChanges = $formattedChanges->map(function ($change, $key) use ($formatKey) {
                $from = is_array($change['from']) ? json_encode($change['from']) : $change['from'];
                $to = is_array($change['to']) ? json_encode($change['to']) : $change['to'];

                return $formatKey($key) . ' changed from "' . $from . '" to "' . $to . '"';
            })->implode(', ');

            // Build formatted properties with pretty keys
            $properties = [
                'attributes'    => $formattedChanges->mapWithKeys(fn($v, $k) => [$formatKey($k) => $v['to']]),
                'old'           => $formattedChanges->mapWithKeys(fn($v, $k) => [$formatKey($k) => $v['from']]),
                'ip'            => $ip,
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
