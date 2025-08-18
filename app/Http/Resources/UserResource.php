<?php

namespace App\Http\Resources;

use DateTime;
use DateTimeZone;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        switch ($this->goal_type) {
            case '0':
                $goal_type =  __('message.track_cycle');
                break;
            case '1':
                $goal_type =  __('message.track_pragnancy');
                break;
            default:
                # code...
                $goal_type = null;
                break;
        }

        return [
            'id'                => $this->id,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'display_name'      => $this->display_name,
            'email'             => $this->email,
            'goal_type'         => $this->goal_type,
            'goal_type_name'    => $goal_type,
            'period_start_date' => $this->period_start_date,
            'region'            => $this->region,
            'country_name'      => $this->country_name,
            'country_code'      => $this->country_code,
            'city'              => $this->city,
            'cycle_length'      => $this->cycle_length,
            'period_length'     => $this->period_length,
            'luteal_phase'      => $this->luteal_phase,
            'user_type'         => $this->user_type,
            'age'               => $this->age,
            'profile_image'     => getSingleMedia($this, 'profile_image',null),
            'login_type'        => $this->login_type,
            'player_id'         => $this->player_id,
            'timezone'          => $this->timezone,
            'is_linked'         => $this->is_linked,
            'partner_name'      => $this->partner_name,
            'is_subscription'   => ($this->is_subscription == 1) ? true : false,
            'last_notification_seen' => $this->last_notification_seen,
            'status'            => $this->status,
            'last_sync_date'    => $this->last_sync_date,
            // 'encrypted_user_data'    => $this->encrypted_user_data,
            'is_backup'         => $this->is_backup,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
