<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubSymptomsResource extends JsonResource
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
        $health_expert = optional($article->health_experts);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'bg_color' => $this->bg_color,
            'article'     => new ArticleResource($article),
            'sub_symptoms' => $this->subSymptoms->map(function ($subSymptom) {
                $subSymptom_article = optional($subSymptom->article);
                return [
                    'id' => $subSymptom->id,
                    'title' => $subSymptom->title,
                    'sub_symptom_icon' => getSingleMedia($subSymptom, 'sub_symptom_icon', null),
                    'description' => $subSymptom->description,
                    'article'     => new ArticleResource($subSymptom_article),
                ];
            }),
        ];
    }

}
