<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DefaultLogCategoryResource extends JsonResource
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
            'type'                 => $this->type,
            'name'                 => $this->name,
            'article'              => $this->blog_link != null ? new ArticleResource($article) : [],
            'status'               => $this->status,
            'default_log_category_image'   => getSingleMedia($this, 'log_category_image',null),
            'description'          => $this->description,
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
        ];
    }
}
