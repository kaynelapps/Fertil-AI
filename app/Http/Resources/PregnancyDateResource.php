<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PregnancyDateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $article = optional($this->article);
        return [
            'id'                   => $this->id,
            'pregnancy_date'       => $this->pregnancy_date.' '.  __('message.week') ?? null,
            'title'                => $this->title,
            'status'               => $this->status,
            'pregnancy_date_image' => getSingleMedia($this, 'pregnancy_date_image',null),
            'article'              => new ArticleResource($article),
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
        ];
    }
}
