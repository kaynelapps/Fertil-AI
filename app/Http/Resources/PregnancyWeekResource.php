<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PregnancyWeekResource extends JsonResource
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
        } 

        return [
            'id'                   => $this->id,
            'title'                => $this->title,
            'thumbnail_image'      => getSingleMedia($this, 'thumbnail_image',null),
            'goal_type'            => $this->goal_type,
            'goal_type_name'       => $goal_type, 
            'view_type'            => $this->view_type,
            'weeks'                => $this->weeks .' '. 'week',
            'view_type_name'       => $view_type,
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
