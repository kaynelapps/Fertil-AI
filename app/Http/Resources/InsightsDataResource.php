<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InsightsDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->goal_type == 0) {
            $goal_type =  __('message.track_cycle');
        } elseif ($this->goal_type == 1) {
            $goal_type =  __('message.track_pragnancy');
        }

        if ($this->view_type == 0) {
            $view_type =  __('message.story_view');
        } elseif ($this->view_type == 1) {
            $view_type =  __('message.video');
        } elseif ($this->view_type == 2) {
            $view_type =  __('message.categories');
        }

        return [
            'id'                   => $this->id,
            'title'                => $this->title,
        ];
    }
}
