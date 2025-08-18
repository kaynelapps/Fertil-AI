<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageSectionResource extends JsonResource
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
        $goal_type = null;
        switch ($this->goal_type) {
            case '0':
                $goal_type =  __('message.track_cycle');
                break;
            case '1':
                $goal_type =  __('message.track_pragnancy');
                break;
            default:
                # code...
                $goal_type = null;
                break;
        }
        return [
            'id'                   => $this->id,
            'title'                => $this->title,
            'goal_type'            => $this->goal_type,
            'goal_type_name'       => $goal_type,
            'category_id'          => $this->category_id,
            'category_name'        => $this->name,
            'status'               => $this->status,
            'article'              => $this->article_id != null ? new ArticleResource($article) : [],
            'image_section_thumbnail_image' => getSingleMedia($this, 'image_section_thumbnail_image',null),
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
        ];
    }
}
