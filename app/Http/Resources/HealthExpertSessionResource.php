<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HealthExpertSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $health_expert = optional($this->health_expert);
        $days = [
            1 => __('message.monday'),
            2 => __('message.tuesday'),
            3 => __('message.wednesday'),
            4 => __('message.thursday'),
            5 => __('message.friday'),
            6 => __('message.saturday'),
            7 => __('message.sunday'),
        ];
        $days_data = [];
        foreach ($this->week_days as $key => $value) {
            # code...
            $days_data[] = [
                'day' => $value,
                'day_name' => substr($days[$value], 0, 3),
            ];
        }
        return [
            'id'                 => $this->id,
            'health_expert_id'   => $this->health_expert_id,
            'health_expert_name' => optional($health_expert->users)->display_name,
            'health_experts_image' => getSingleMedia($health_expert, 'health_experts_image',null),
            'week_days'          => $days_data,
            'morning_start_time' => date('H:i', strtotime($this->morning_start_time)),
            'morning_end_time'   => date('H:i', strtotime($this->morning_end_time)),
            'evening_start_time' => date('H:i', strtotime($this->evening_start_time)),
            'evening_end_time'   => date('H:i', strtotime($this->evening_end_time)),
        ];
    }
}
