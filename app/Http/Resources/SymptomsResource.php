<?php

namespace App\Http\Resources;

use App\Models\SubSymptoms;
use Illuminate\Http\Resources\Json\JsonResource;

class SymptomsResource extends JsonResource
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
        $expert = optional($article->health_experts);
        // dd($expert);
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'bg_color'    => $this->bg_color,
            'article'     => $this->article_id != null ? new ArticleResource($article) : [],
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            // 'sub_symptoms'=> $sub_symptoms,
        ];
    }
}
