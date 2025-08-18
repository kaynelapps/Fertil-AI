<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InsightsResource extends JsonResource
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

        $view_type = null;
        if ($this->view_type == 0) {
            $view_type =  __('message.story_view');
        } elseif ($this->view_type == 1) {
            $view_type =  __('message.video');
        } elseif ($this->view_type == 2) {
            $view_type =  __('message.categories');
        } elseif ($this->view_type == 3) {
            $view_type =  __('message.image_video');
        }

        return [
            'id'                   => $this->id,
            'title'                => $this->title,
            'thumbnail_image'      => getSingleMedia($this, 'thumbnail_image',null),
            'symptoms_id'          => optional($this->symptom)->id,
            'symptoms_title'       => optional($this->symptom)->title,
            'sub_symptoms_id'      => optional($this->subSymptoms)->id,
            'sub_symptoms_title'   => optional($this->subSymptoms)->title,
            'insights_type'        => $this->insights_type,
            'goal_type'            => $this->goal_type,
            'goal_type_name'       => $goal_type, // (0: track cycle,1: get pragnent,2: track pragnancy) 
            'view_type'            => $this->view_type,
            'view_type_name'       => $view_type, // (0: story_view,1: video,2:categories)
            'insight_data'         => json_decode($this->insights_data, true) ?? null,
            'video_data'           => getMediaFileExit(optional($this->video), 'videos_upload') ? getSingleMedia(optional($this->video), 'videos_upload', null) : null,
            'insights_video'       => getSingleMedia($this, 'insights_video',null),
            'story_image'          => getAttachmentArray( $this->getMedia('story_image'), null),
            'image_video_image'    => getSingleMedia($this, 'image_video_image',null),
            'video_image_video'    => getSingleMedia($this, 'video_image_video',null),
            'article'              => new ArticleResource(optional($this->article)),
            'category_id'          => optional($this->category)->id,
            'category_name'        => optional($this->category)->name,
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
        ];
    }
}
