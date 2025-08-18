<?php

namespace App\Http\Resources;

use App\Models\Article;
use App\Models\CycleDateData;
use Illuminate\Http\Resources\Json\JsonResource;

class CaycleDaysResource extends JsonResource
{
    public function toArray($request)
    {
        if ($this->goal_type == 0) {
            $goal_type = __('message.track_cycle');
        } elseif ($this->goal_type == 1) {
            $goal_type = __('message.track_pragnancy');
        }

        $view_type = null;
        if ($this->view_type == 0) {
            $view_type = __('message.story_view');
        } elseif ($this->view_type == 1) {
            $view_type = __('message.video');
        } elseif ($this->view_type == 2) {
            $view_type = __('message.categories');
        } elseif ($this->view_type == 3) {
            $view_type = __('message.image_video');
        } elseif ($this->view_type == 4) {
            $view_type = __('message.blog_course');
        } elseif ($this->view_type == 5) {
            $view_type = __('message.podcast');
        } elseif ($this->view_type == 6) {
            $view_type = __('message.article');
        } elseif ($this->view_type == 7) {
            $view_type = __('message.text_message');
        } elseif ($this->view_type == 8) {
            $view_type = __('message.question_answer');
        }
        if ($this->view_type == 4) {
            $articles = Article::whereIn('id', $this->blog_course_article_id)->get();
            $articleData = ArticleResource::collection($articles);
        } else {
            $articleData = new ArticleResource(optional($this->article));
        }
        // For Text Message & Question Answer
        if ($this->view_type == 7 || $this->view_type == 8) {
            $cycleDateData = CycleDateData::where('cycle_date_id', $this->id)->get();
            // dd($this->id);

            return [
                'id' => $this->id,
                'title' => is_array($this->title) ? implode(', ', $this->title) : $this->title,
                'thumbnail_image' => getSingleMedia($this, 'thumbnail_image', null),
                'goal_type' => $this->goal_type,
                'goal_type_name' => $goal_type,
                'view_type' => $this->view_type,
                'view_type_name' => $view_type,
                'article' => $articleData ?? null, // Article moved outside the list
                'cycle_date_data' => collect(
                    CycleDateDataResource::collection(
                        $cycleDateData->map(function ($data) {
                            $data->hide_article = true;
                            return $data;
                        })
                    )->toArray(request())
                )->filter()->values(),

            ];
        }

        // For all other view types
        return [
            'id' => $this->id,
            'title' => $this->title,
            'thumbnail_image' => getSingleMedia($this, 'thumbnail_image', null),
            'goal_type' => $this->goal_type,
            'goal_type_name' => $goal_type,
            'view_type' => $this->view_type,
            'view_type_name' => $view_type,
            'video_data' => getMediaFileExit(optional($this->video), 'videos_upload') ? getSingleMedia(optional($this->video), 'videos_upload', null) : null,
            'story_image' => getAttachmentArray($this->getMedia('story_image'), null),
            'image_video_image' => getSingleMedia($this, 'image_video_image', null),
            'video_image_video' => getSingleMedia($this, 'video_image_video', null),
            'article' => $articleData ?? null,
            'category' => new CategoryResource(optional($this->category)),
            'section_data_podcast' => getSingleMedia($this, 'section_data_podcast'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }


}
