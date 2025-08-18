<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AskexpertResource extends JsonResource
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
            'id'                   => $this->id,
            'user'                 => new UserResource($this->users),
            'title'                => $this->title,
            'description'          => $this->description,
            'expert'               =>  new HealthExpertResource($this->healthexpert),
            'expert_answer'        => $this->expert_answer,
            'status'               => $this->status, 
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
            'askexpert_image'      => getAttachments($this->getMedia('askexpert_image')),
        ];
    }
}
