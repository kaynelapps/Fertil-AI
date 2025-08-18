<?php

namespace App\Http\Resources;

use App\Models\ArticleReference;
use App\Models\BookmarkActicle;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Tags;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tags = null;
        if(isset($this->tags_id)) {
            $tags = Tags::whereIn('id', $this->tags_id)->get()->map(function ($item) {
                return [
                    'id'   => $item->id,
                    'name' => $item->name,
                ];
            });
        }

        // $reference = [];
        $reference = ArticleReference::where('article_id',$this->id)->get()->map(function($q){
            return [
                'id' => $q->id,
                'reference_name' => $q->reference,
            ];
        });

        $goal_type = null;
        if ($this->goal_type == 0) {
            $goal_type =  __('message.track_cycle');
        } elseif ($this->goal_type == 1) {
            $goal_type =  __('message.track_pragnancy');
        } 
        
        $health_experts = optional($this->health_experts);
        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'tags'                 => $tags,
            'article_type'        => $this->article_type,
            'goal_type'            => $this->goal_type,
            'type'                 => $this->type,
            'goal_type_name'       => $goal_type,
            'bookmark'             => $this->is_bookmark,
            'description'          => $this->description,
            'expert_data' => [
                'id' => $this->expert_id,
                'name' => optional($health_experts->users)->display_name,
                'tag_line' =>$health_experts->tag_line,
                'health_experts_image' => $health_experts ? getSingleMedia($health_experts, 'health_experts_image',null) : '',
            ],
            'article_reference'    => $reference,
            'article_image'        => getSingleMedia($this, 'article_image',null),
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
        ];
    }
}