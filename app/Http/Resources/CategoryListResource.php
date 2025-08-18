<?php

namespace App\Http\Resources;

use App\Models\SectionDataMain;
use App\Models\VideosUpload;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CategoryListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->goal_type == 0) {
            $goal_type =  __('message.track_cycle');
        } elseif ($this->goal_type == 1) {
            $goal_type =  __('message.track_pragnancy');
        }

        return [
            'id'                        => $this->id,
            'title'                     => $this->name,
            'goal_type'                 => $this->goal_type,
            'goal_type_name'            => $goal_type, // (0: track cycle,1: get pragnent,2: track pragnancy) 
            'description'               => $this->description,
            'category_image'            => getSingleMedia($this, 'header_image',null),
            'category_thumbnail_image'  => getSingleMedia($this, 'category_thumbnail_image',null),
            'created_at'                => $this->created_at,
        ];
    }
}
