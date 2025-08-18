<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
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
            'id'             => $this->id,
            'question'       => $this->question,
            'answer'         => $this->answer,
            'goal_type'      => $this->goal_type,
            'goal_type_name' => getGoalType()[$this->goal_type] ?? null,
            'category_id'    => $this->category_id,
            'category_name'  => optional($this->category)->name,
            'url'            => $this->url,
            'status'         => $this->status,
            'article'        => $this->article_id != null ? new ArticleResource($article) : null,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
