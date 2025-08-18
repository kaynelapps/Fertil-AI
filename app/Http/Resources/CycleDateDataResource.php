<?php

namespace App\Http\Resources;

use App\Models\CycleDateData;
use Illuminate\Http\Resources\Json\JsonResource;

class CycleDateDataResource extends JsonResource
{
   public function toArray($request)
    {
        $article = optional($this->article);

        if ($this->slide_type == 1) {
            $slides = CycleDateData::where('cycle_date_id', $this->cycle_date_id)
                ->where('slide_type', 1)
                ->orderBy('id')
                ->get();

            $groupedSlides = $slides->chunk(2)->map(function ($group) {
                $questionAnswers = [];
                foreach ($group as $item) {
                    if (!empty($item->question) || !empty($item->answer)) {
                        $questionAnswers[] = [
                            'question' => $item->question,
                            'answer'   => $item->answer,
                        ];
                    }
                }

                if (count($questionAnswers)) {
                    return [
                        'ids' => $group->pluck('id')->toArray(),
                        'questionAnswers' => $questionAnswers
                    ];
                }

                return null;
            })->filter()->values();

            $currentGroup = $groupedSlides->first(function ($group) {
                return in_array($this->id, $group['ids']);
            });

            if (!$currentGroup || $this->id !== $currentGroup['ids'][0]) {
                return null;
            }
        }

        if (!$this->title && !$this->answer) {
            return null;
        }
        $response = [
            'id' => $this->id,
            'slide_type' => $this->slide_type,
            'title' => $this->title,
            'message' => $this->message,
            'cycle_date_data_text_message_image' => getSingleMedia($this, 'cycle_date_data_text_message_image', null),
            'cycle_date_data_que_ans_image_1' => getSingleMedia($this, 'cycle_date_data_que_ans_image_1', null),
            'cycle_date_data_que_ans_image_2' => getSingleMedia($this, 'cycle_date_data_que_ans_image_2', null),
            'cycle_date_id' => $this->cycle_date_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if (empty($this->hide_article)) {
            $response['article'] = new ArticleResource($article) ?? [];
        }

        if ($this->slide_type == 1 && !empty($currentGroup)) {
            $response['questionanswer'] = $currentGroup['questionAnswers'];
        }

        return $response;
    }

}
