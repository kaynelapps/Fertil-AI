<?php

namespace App\Http\Resources;

use App\Models\Article;
use App\Models\CommonQuestions;
use App\Models\ImageSection;
use App\Models\SectionDataMain;
use App\Models\Sections;
use App\Models\VideosUpload;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
            $sections_dataa = SectionDataMain::where('category_id', $this->id)->has('section_data','>','0')->orderBy('dragondrop', 'asc')->get()->map(function ($section_data_main) {
                $subSectionData = $section_data_main->section_data->map(function ($section_data) {
                    $videos = Media::where('model_type', 'App\Models\VideosUpload')
                        ->where('collection_name', 'videos_upload')
                        ->whereIn('model_id', (array)($section_data->video_id ?? []))
                        ->get();

                    $videosList = $videos->map(function ($media) {
                        $videoUpload = VideosUpload::where('status',1)->find($media->model_id);

                        if ($videoUpload) {
                            return [
                                'id' => $media->model_id,
                                'video_title' => $videoUpload->title,
                                'video_duration' => $videoUpload->video_duration,
                                'thumbnail_image' => getSingleMedia($videoUpload, 'upload_video_thumbnail_image',null),
                                'file_url' => $videoUpload->getFirstMediaUrl('videos_upload'),
                            ];
                        }

                        return null;
                    })->filter()->values();

                    $view_type_name = null;
                    $article = [];
                    if ($section_data->view_type == 0) {
                         $view_type_name =  __('message.story_view');
                         $article = Article::where('id',$section_data->article_id)->get();
                    } elseif ($section_data->view_type == 1) {
                         $view_type_name =  __('message.video');
                    } elseif ($section_data->view_type == 2) {
                         $view_type_name =  __('message.categories');
                    } elseif ($section_data->view_type == 3) {
                         $view_type_name =  __('message.video_course');
                    } elseif ($section_data->view_type == 4) {
                         $article = Article::whereIn('id',$section_data->blog_course_article_id)->get();
                         $view_type_name =  __('message.blog_course');
                    } elseif ($section_data->view_type == 5) {
                         $view_type_name =  __('message.podcast');
                    } elseif ($section_data->view_type == 6) {
                         $article = Article::where('id',$section_data->blog_article_id)->get();
                         $view_type_name =  __('message.blog');
                    }

                    $article = ArticleResource::collection($article);

                    return [
                        'id' => $section_data->id ?? '',
                        'title' => $section_data->title ?? '',
                        'view_type' => $section_data->view_type,
                        'view_type_name' => $view_type_name,
                        'description' => $section_data->description ?? '',
                        'category_id' => optional($section_data->category)->id,
                        'category_name' => optional($section_data->category)->name ?? '',
                        'article' => $article,
                        'section_data_image' =>  getSingleMedia($section_data, 'section_data_image') ,
                        'section_data_story_image' => getAttachmentArray($section_data->getMedia('section_data_story_image'), null),
                        'section_data_video' => $videosList,
                        'section_data_podcast' => getSingleMedia($section_data, 'section_data_podcast'),
                        'status' => $section_data->status
                    ];
                });

                return [
                    'id' => $section_data_main->id ?? '',
                    'title' => $section_data_main->title ?? '',
                    'category_id' => optional($section_data_main->category)->id,
                    'category_name' => optional($section_data_main->category)->name ?? '',
                    'sub_section_data' => $subSectionData,
                ];
            });


        $common_que_section = CommonQuestions::where('status',1)->with('article','article.health_experts')->where('category_id', $this->id)->get()->map(function($common_que) {
            return [
                'id' => $common_que->id,
                // 'goal_type' =>  $common_que->goal_type,
                'question' => $common_que->question,
                'answer' => $common_que->answer,
                'status' => $common_que->status,
                'article'  => $common_que->article_id != null ? new ArticleResource(optional($common_que->article)) : null,
            ];
        });
        
        $info_sections = Sections::where('category_id', $this->id)->orderBy('created_at', 'desc')->get()->map(function($section) {
            return [
                'id'=> $section->id,
                'title'=> $section->title,
                'description'=> $section->description,
                'info_section_image'=> getSingleMedia($section, 'info_section_image',null),
            ];
        });

        $image_sections = ImageSection::where('category_id', $this->id)->where('status',1)->orderBy('created_at', 'desc')->get()->map(function($query) {
            $get_article = optional($query->article);
            return [
                'id'        => $query->id,
                'title'     => $query->title,
                'goal_type' => $query->goal_type,
                'url'       => $query->url,
                'article'   => $query->article_id != null ? new ArticleResource(optional($query->article)) : [],
                'image_section_thumbnail_image'   => getSingleMedia($query,'image_section_thumbnail_image',null),
            ];
        });

        return [
            'id'                        => $this->id,
            'title'                     => $this->name,
            'goal_type'                 => $this->goal_type,
            'goal_type_name'            => getGoalType()[$this->goal_type] ?? null,
            'description'               => $this->description,
            'category_image'            => getSingleMedia($this, 'header_image',null),
            'created_at'                => $this->created_at,
            'image_section'             => $image_sections,
            'info_sections'             => $info_sections,
            'common_que_section_data'   => $common_que_section,
            'section_data_main_list'    => $sections_dataa,
        ];
    }
}
