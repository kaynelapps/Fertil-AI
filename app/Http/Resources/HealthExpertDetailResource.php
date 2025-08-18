<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HealthExpertDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'name'                => optional($this->users)->display_name,
            'email'               => optional($this->users)->email,
            'tag_line'            => $this->tag_line,
            'status'              => optional($this->users)->status,
            'is_access'           => $this->is_access,
            'health_experts_image' => getSingleMedia($this, 'health_experts_image',null),
            'short_description'   => $this->short_description,
            'career'              => $this->career,
            'education'           => $this->education,
            'awards_achievements' => $this->awards_achievements,
            'area_expertise'      => $this->area_expertise,
        ];
    }
}
