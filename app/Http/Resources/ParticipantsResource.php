<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantsResource extends JsonResource
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
            'id'                     => $this->id,
            'user_name'              => optional($this->users)->display_name ?? '',
            'registration_date'      => $this->registration_date ?? '',
            'registration_cancelled' => $this->is_cancelled ?? '',
            'created_at'             => $this->created_at,
        ];
    }
}
