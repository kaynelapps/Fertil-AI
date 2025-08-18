<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $users = optional($this->users);
        return [
            'id'                   => $this->id,
            'user_id'              => $this->user_id,
            'user_name'            => optional($this->users)->display_name,
            'profile_image'        => getSingleMedia($users, 'profile_image',null),
            'review'               => $this->review,
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
        ];
    }
}
