<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CategoryListResource;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Models\Article;
use App\Models\CustomTopic;
use App\Models\SectionData;
use App\Models\SectionDataMain;
use App\Models\VideosUpload;
use App\Traits\EncryptionTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CategoryController extends Controller
{
    use EncryptionTrait;

    public function getList(Request $request)
    {
        if(env('DATA_ENCRYPTION')){
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json([
                    'responseData' => $this->encryptData(['error' => __('message.invalid_data')])
                ]);
            }
        }else{
            $decryptedData = $request->all();
        }
        $input = $decryptedData;
        
        $goal_type = $input['goal_type'] ?? auth()->user()->goal_type;

        $categories = Category::where('goal_type', $goal_type)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();
            $sectionData = [];
            // foreach ($categories as $category) {
            $sections = SectionDataMain::where('is_show_insights', 1)
                ->has('section_data', '>', '0')
                ->where('goal_type',$goal_type)
                ->orderBy('dragondrop', 'asc') 
                ->get()
                ->map(function ($section_data_main) {
                    $subSectionData = $section_data_main->section_data()
                        ->orderBy('created_at', 'desc') 
                        ->get()
                        ->map(function ($section_data) {
                            $videos = Media::where('model_type', 'App\Models\VideosUpload')
                                ->where('collection_name', 'videos_upload')
                                ->whereIn('model_id', (array)($section_data->video_id ?? []))
                                ->get();
        
                            $videosList = $videos->map(function ($media) {
                                $videoUpload = VideosUpload::find($media->model_id);
        
                                if ($videoUpload) {
                                    return [
                                        'id' => $media->model_id,
                                        'video_title' => $videoUpload->title,
                                        'video_duration' => $videoUpload->video_duration,
                                        'thumbnail_image' => getSingleMedia($videoUpload, 'upload_video_thumbnail_image', null),
                                        'file_url' => $videoUpload->getFirstMediaUrl('videos_upload'),
                                    ];
                                }
        
                                return null;
                            })->filter()->values();
        
                            $view_type_name = null;
                            $article = [];
                            if ($section_data->view_type == 0) {
                                $view_type_name = __('message.story_view');
                                $article = Article::where('id', $section_data->article_id)
                                    ->orderBy('id', 'desc') 
                                    ->get();
                            } elseif ($section_data->view_type == 1) {
                                $view_type_name = __('message.video');
                            } elseif ($section_data->view_type == 2) {
                                $view_type_name = __('message.categories');
                            } elseif ($section_data->view_type == 3) {
                                $view_type_name = __('message.video_course');
                            } elseif ($section_data->view_type == 4) {
                                $article = Article::whereIn('id', $section_data->blog_course_article_id)
                                    ->orderBy('id', 'desc') 
                                    ->get();
                                $view_type_name = __('message.blog_course');
                            } elseif ($section_data->view_type == 5) {
                                $view_type_name = __('message.podcast');
                            } elseif ($section_data->view_type == 6) {
                                $article = Article::where('id', $section_data->blog_article_id)
                                    ->orderBy('id', 'desc')
                                    ->get();
                                $view_type_name = __('message.blog');
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
                                'section_data_image' => getSingleMedia($section_data, 'section_data_image'),
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
              $customTopics = CustomTopic::where('goal_type', $goal_type)->get()->map(function ($topic) {

                $topicIds = $topic->topic_ids ? json_decode($topic->topic_ids) : [];

                $sectionDatas = SectionData::whereIn('id', $topicIds)->get();

                $subSectionData = $sectionDatas->map(function ($section_data) {

                    // Fetch videos
                    $videos = Media::where('model_type', 'App\Models\VideosUpload')
                        ->where('collection_name', 'videos_upload')
                        ->whereIn('model_id', (array)($section_data->video_id ?? []))
                        ->get();

                    $videosList = $videos->map(function ($media) {
                        $videoUpload = VideosUpload::find($media->model_id);
                        if ($videoUpload) {
                            return [
                                'id' => $media->model_id,
                                'video_title' => $videoUpload->title,
                                'video_duration' => $videoUpload->video_duration,
                                'thumbnail_image' => getSingleMedia($videoUpload, 'upload_video_thumbnail_image', null),
                                'file_url' => $videoUpload->getFirstMediaUrl('videos_upload'),
                            ];
                        }
                        return null;
                    })->filter()->values();

                    // View type name & articles
                    $view_type_name = null;
                    $article = [];
                    if ($section_data->view_type == 0) {
                        $view_type_name = __('message.story_view');
                        $article = Article::where('id', $section_data->article_id)->orderBy('id', 'desc')->get();
                    } elseif ($section_data->view_type == 1) {
                        $view_type_name = __('message.video');
                    } elseif ($section_data->view_type == 2) {
                        $view_type_name = __('message.categories');
                    } elseif ($section_data->view_type == 3) {
                        $view_type_name = __('message.video_course');
                    } elseif ($section_data->view_type == 4) {
                        $article = Article::whereIn('id', $section_data->blog_course_article_id)->orderBy('id', 'desc')->get();
                        $view_type_name = __('message.blog_course');
                    } elseif ($section_data->view_type == 5) {
                        $view_type_name = __('message.podcast');
                    } elseif ($section_data->view_type == 6) {
                        $article = Article::where('id', $section_data->blog_article_id)->orderBy('id', 'desc')->get();
                        $view_type_name = __('message.blog');
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
                        'section_data_image' => getSingleMedia($section_data, 'section_data_image'),
                        'section_data_story_image' => getAttachmentArray($section_data->getMedia('section_data_story_image'), null),
                        'section_data_video' => $videosList,
                        'section_data_podcast' => getSingleMedia($section_data, 'section_data_podcast'),
                        'status' => $section_data->status
                    ];
                });

                // Return CustomTopic with its sub_section_data
                return [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'sub_section_data' => $subSectionData
                ];
            });
        
            // Merge sections with the main sectionData
            $sectionData = array_merge($sectionData, $sections->toArray());
        // } 

        $categoryItems = CategoryListResource::collection($categories);

        $allSectionData = collect($sectionData)->merge($customTopics)->values();

        $response = [
            'category_data' => $categoryItems,
            'section_data' => $allSectionData,
        ];

        return response()->json([
            'responseData' => $this->encryptData($response)
        ]);
        // return json_custom_response($response);
    }


    public function getCategoryData(Request $request)
    {
        $category_id = $request->category_id ?? null;
        $user_goal_type = auth()->user()->goal_type;
        $category = Category::where('id', $category_id)->where('goal_type', $user_goal_type)->where('status', 1)->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'responseData' => $this->encryptData(CategoryResource::collection($category)->first())
        ]);
        // return response()->json($items, 200);
    }
}
