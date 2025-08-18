<?php

namespace App\Http\Controllers;

use App\DataTables\CycleDatesDataDataTable;
use App\DataTables\CycleDatesDataTable;
use App\Http\Requests\CycleDatesRequest;
use App\Models\Article;
use App\Models\CycleDateData;
use App\Models\CycleDates;
use App\Models\VideosUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CycleDatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CycleDatesDataTable $dataTable)
    {
        if (!Auth::user()->can('cycledays-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.list_form_title',['form' => __('message.cycle_dates')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $params = [
            'goal_type' => request('goal_type') ?? null,
            'view_type' => request('view_type') ?? null,
            'day' => request('day') ?? null,
        ];
        $delete_checkbox_checkout = $auth_user->can('cycledays-delete') ? '<button id="deleteSelectedBtn" checked-title = "cycle-day-checked " class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('cycledays-add') ? '<a href="'.route('cycle-dates.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.cycle_dates')]).'</a>' : '';
        $filter = $auth_user->can('cycledays-add') ? '<a href="'.route('cycledays.filter',$params).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>' : '';
        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $newDayValue = CycleDates::pluck('day')->toArray(); 
        if (!Auth::user()->can('cycledays-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        
        $pageTitle = __('message.add_form_title', ['form' => __('message.cycle_dates')]);
        
        $allDays = range(1, 50);
        $availableDays = array_diff($allDays, $newDayValue);

        $videos = Media::where('model_type', 'App\Models\VideosUpload')
              ->where('collection_name', 'videos_upload')
              ->pluck('file_name','id')
              ->toArray();

        $videosList = [];
        foreach ($videos as $mediaId => $name) {
            $videoUpload = VideosUpload::whereHas('media', function ($query) use ($mediaId) {
                $query->where('id', $mediaId);
            })->first();

            if ($videoUpload) {
                $videosList[$videoUpload->id] = $videoUpload->title;
            }
        }

        $video_couse = Media::where('model_type', 'App\Models\VideosUpload')
            ->where('collection_name', 'videos_upload')
            ->pluck('file_name','id')
            ->toArray();
        $videosCourseList = [];
        foreach ($video_couse as $mediaId => $name) {
            $videoUpload = VideosUpload::whereHas('media', function ($query) use ($mediaId) {
                $query->where('id', $mediaId);
            })->first();
        
            if ($videoUpload) {
                $videosCourseList[$videoUpload->id] = $videoUpload->title;
            }
        }
        $selected_blog_course_article = null;
        
        return view('cycle-dates.form', compact('pageTitle', 'availableDays','videosCourseList','videosList','selected_blog_course_article'));
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(CycleDatesRequest $request)
    // {
    //     if (auth()->user()->hasRole('super_admin')) {
    //         $message = __('message.demo_permission_denied');
    //         if (request()->ajax()) {
    //             return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
    //         }
    //         return redirect()->route('cycle-dates.index')->withErrors($message);
    //     }

    //     if (!Auth::user()->can('cycledays-add')) {
    //         $message = __('message.demo_permission_denied');
    //         return redirect()->back()->withErrors($message);
    //     }
    //     if($request->view_type == 2) {
    //         $data['category_id'] = $request->category_id;
    //     } else {
    //         $data['category_id'] = NULL;
    //     }

    //     $data = $request->all();
    //    $cycleDate =  CycleDates::create($data);

    //     if ($cycleDate->view_type == 3) {
    //         uploadMediaFile($cycleDate,$request->image_video_image, 'image_video_image');
    //         uploadMediaFile($cycleDate,$request->video_image_video, 'video_image_video');
    //     }
    //     uploadMediaFile($cycleDate,$request->thumbnail_image, 'thumbnail_image');

    //     if ($request->hasFile('story_image')) {
    //         foreach ($request->file('story_image') as $image) {
    //             $cycleDate->addMedia($image)->toMediaCollection('story_image');
    //         }
    //     };

    //     $message = __('message.save_form', ['form' => __('message.cycle_dates')]);        

    //     return redirect()->route('cycle-dates.index')->withSuccess($message);
    // }
    public function store(CycleDatesRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('cycle-dates.index')->withErrors($message);
        }
        $data = $request->only([
            'title',
            'goal_type',
            'day',
            'view_type',
            'category_id',
            'type',
            'thumbnail_image',
            'status',
            'article_id',
            'video_id',
            'blog_course_article_id',
        ]);
        if ($request->view_type != 2) {
            $data['category_id'] = null;
        }
        $cycleDate = CycleDates::create($data);

        if ($cycleDate->view_type == 3) {
            uploadMediaFile($cycleDate, $request->image_video_image, 'image_video_image');
            uploadMediaFile($cycleDate, $request->video_image_video, 'video_image_video');
        }
        uploadMediaFile($cycleDate, $request->cycle_dates_thumbnail_image, 'cycle_dates_thumbnail_image');
           uploadMediaFile($cycleDate, $request->section_data_podcast, 'section_data_podcast');

        if ($request->hasFile('story_image')) {
            foreach ($request->file('story_image') as $image) {
                $cycleDate->addMedia($image)->toMediaCollection('story_image');
            }
        }

        if ($cycleDate->view_type == 8) {
            // Store Question Answer data
            $article_ids = $request->article_id ?? null;
            $qaGroups = $request->title_name;  // title_name[groupIndex][]

            foreach ($qaGroups as $groupIndex => $titles) {
                for ($i = 0; $i < count($titles); $i++) {
                    $cycleDateDataArr = [
                        'cycle_date_id' => $cycleDate->id,
                        'slide_type'    => 1, // for question-answer
                        'article_id'    => $article_ids,
                        'title'         => $titles[$i],
                        'message'       => null,
                        'status'        => 1,
                        'question'      => $request->qa_question[$groupIndex][$i] ?? null,
                        'answer'        => $request->qa_answer[$groupIndex][$i] ?? null,
                    ];

                    $cycle_date_data = CycleDateData::create($cycleDateDataArr);

                    // Store image for each question-answer row
                    if (isset($request->file('cycle_date_data_que_ans_image')[$groupIndex][$i])) {
                        uploadMediaFile(
                            $cycle_date_data, 
                            $request->file('cycle_date_data_que_ans_image')[$groupIndex][$i], 
                            'cycle_date_data_que_ans_image'
                        );
                    }
                }
            }
        } 
        if ($cycleDate->view_type == 7) {
            $titleAnswers = is_array($request->title_answer) ? $request->title_answer : [$request->title_answer];
            $answers      = is_array($request->answer) ? $request->answer : [$request->answer];
            $statuses     = is_array($request->status) ? $request->status : [$request->status];
            $images       = $request->file('cycle_date_data_text_message_image') ?? [];

            foreach ($titleAnswers as $key => $title) {
                $cycleDateDataArr = [
                    'cycle_date_id' => $cycleDate->id,
                    'slide_type'    => 0, // text message
                    'article_id'    => $request->article_id ?? null,
                    'title'         => $title,
                    'message'       => $answers[$key] ?? null,
                    'status'        => $statuses[$key] ?? 1,
                    'question'      => null,
                    'answer'        => null,
                ];

                $cycle_date_data = CycleDateData::create($cycleDateDataArr);

                if (isset($images[$key])) {
                    uploadMediaFile(
                        $cycle_date_data, 
                        $images[$key], 
                        'cycle_date_data_text_message_image'
                    );
                }
            }
        }

        $message = __('message.save_form', ['form' => __('message.cycle_dates')]);
        return redirect()->route('cycle-dates.index')->withSuccess($message);
    }

    public function edit($id)
    {
        $pageTitle = __('message.update_form_title', ['form' => __('message.cycle_dates')]);

        $data = CycleDates::find($id);
        $textmessage_data = CycleDateData::where('cycle_date_id', $id)->where('slide_type', 0)->get();
        $question_answer_data = CycleDateData::where('cycle_date_id', $id)->where('slide_type', 1)->get();

          $videos = Media::where('model_type', 'App\Models\VideosUpload')
              ->where('collection_name', 'videos_upload')
              ->pluck('file_name','id')
              ->toArray();

        $videosList = [];
        foreach ($videos as $mediaId => $name) {
            $videoUpload = VideosUpload::whereHas('media', function ($query) use ($mediaId) {
                $query->where('id', $mediaId);
            })->first();

            if ($videoUpload) {
                $videosList[$videoUpload->id] = $videoUpload->title;
            }
        }
            $selected_blog_course_article = $selected_blog_article = $selected_article =[];
          if(isset($data->blog_course_article_id)){            
            $selected_blog_course_article = Article::whereIn('id', $data->blog_course_article_id)->get()->mapWithKeys(function ($item) {
                return [ $item->id => $item->name ];
            });
        } elseif(isset($data->blog_article_id)){            
            $selected_blog_article = Article::where('id', $data->blog_article_id)->get()->mapWithKeys(function ($item) {
                return [ $item->id => $item->name ];
            });
        } elseif(isset($data->article_id)){            
            $selected_article = Article::where('id', $data->article_id)->get()->mapWithKeys(function ($item) {
                return [ $item->id => $item->name ];
            });
        }

        $video_couse = Media::where('model_type', 'App\Models\VideosUpload')
            ->where('collection_name', 'videos_upload')
            ->pluck('file_name','id')
            ->toArray();
        $videosCourseList = [];
        foreach ($video_couse as $mediaId => $name) {
            $videoUpload = VideosUpload::whereHas('media', function ($query) use ($mediaId) {
                $query->where('id', $mediaId);
            })->first();
        
            if ($videoUpload) {
                $videosCourseList[$videoUpload->id] = $videoUpload->title;
            }
        }

       return view('cycle-dates.form',compact('pageTitle','data','id','textmessage_data','question_answer_data','videosCourseList','selected_blog_course_article','selected_blog_article','selected_article'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('cycle-dates.index')->withErrors($message);
        }

        $data = $request->only([
            'title',
            'goal_type',
            'day',
            'view_type',
            'category_id',
            'type',
            'status',
            'article_id',
            'video_id',
            'blog_course_article_id',
        ]);

        if ($request->view_type != 2) {
            $data['category_id'] = null;
        }
        if(!$request->article_id){
            $data['article_id'] = null;
        }

        $cycleDate = CycleDates::findOrFail($id);
        $cycleDate->update($data);

        if ($cycleDate->view_type == 3) {
            if ($request->hasFile('image_video_image')) {
                $cycleDate->clearMediaCollection('image_video_image');
                uploadMediaFile($cycleDate, $request->image_video_image, 'image_video_image');
            }
            if ($request->hasFile('video_image_video')) {
                $cycleDate->clearMediaCollection('video_image_video');
                uploadMediaFile($cycleDate, $request->video_image_video, 'video_image_video');
            }
        }

        if ($request->hasFile('cycle_dates_thumbnail_image')) {
            $cycleDate->clearMediaCollection('cycle_dates_thumbnail_image');
            uploadMediaFile($cycleDate, $request->cycle_dates_thumbnail_image, 'cycle_dates_thumbnail_image');
        }

        if ($request->hasFile('story_image')) {
            $cycleDate->clearMediaCollection('story_image');
            foreach ($request->file('story_image') as $image) {
                $cycleDate->addMedia($image)->toMediaCollection('story_image');
            }
        }
      
        if ($cycleDate->view_type == 8) {
           $article_ids = $request->article_id ?? null;
            $qaGroups    = $request->title_name; 
            $dataIds     = $request->cycle_date_data_id ?? [];

            $existingIds = [];

            foreach ($qaGroups as $groupIndex => $titles) {
                for ($i = 0; $i < count($titles); $i++) {
                    $cycleDateDataArr = [
                        'cycle_date_id' => $cycleDate->id,
                        'slide_type'    => 1,
                        'article_id'    => $article_ids,
                        'title'         => $titles[$i],
                        'message'       => null,
                        'status'        => 1,
                        'question'      => $request->qa_question[$groupIndex][$i] ?? null,
                        'answer'        => $request->qa_answer[$groupIndex][$i] ?? null,
                    ];

                    $dataId = $dataIds[$groupIndex][$i] ?? null;

                    if ($dataId) {
                       
                        $cycle_date_data = CycleDateData::find($dataId);
                        if ($cycle_date_data) {
                            $cycle_date_data->update($cycleDateDataArr);
                        } else {
                            $cycle_date_data = CycleDateData::create($cycleDateDataArr);
                        }
                    } else {
                        $cycle_date_data = CycleDateData::create($cycleDateDataArr);
                    }
                    $existingIds[] = $cycle_date_data->id;

                     if ($request->hasFile('cycle_date_data_que_ans_image_') && isset($request->file('cycle_date_data_que_ans_image_')[$groupIndex])) {
                $uploadedFiles = $request->file('cycle_date_data_que_ans_image_')[$groupIndex];

                // If file 0 exists → save as image_1
                if (isset($uploadedFiles[0])) {
                    uploadMediaFile(
                        $cycle_date_data,
                        $uploadedFiles[0],
                        'cycle_date_data_que_ans_image_1'
                    );
                }

                // If file 1 exists → save as image_2
                if (isset($uploadedFiles[1])) {
                    uploadMediaFile(
                        $cycle_date_data,
                        $uploadedFiles[1],
                        'cycle_date_data_que_ans_image_2'
                    );
                }
            }
                }
            }

            CycleDateData::where('cycle_date_id', $cycleDate->id)
                ->whereNotIn('id', $existingIds)
                ->delete();
        } 
        if ($cycleDate->view_type == 7) {

            $existingIds = CycleDateData::where('cycle_date_id', $cycleDate->id)
                ->where('slide_type', 0)
                ->pluck('id')
                ->toArray();

            $processedIds = [];

            if ($request->has('title_answer')) {
                foreach ($request->title_answer as $index => $title) {
                    $id = $request->cycle_date_data_ids[$index] ?? null;

                    $cycleDateDataArr = [
                        'cycle_date_id' => $cycleDate->id,
                        'slide_type'    => 0,
                        'article_id'    => $request->article_id ?? null,
                        'title'         => $title,
                        'message'       => $request->answer[$index] ?? null,
                        'status'        => $request->status ?? 1,
                        'question'      => null,
                        'answer'        => null,
                    ];

                    if ($id) {
                        // Update existing
                        $cycle_date_data = CycleDateData::find($id);
                        if ($cycle_date_data) {
                            $cycle_date_data->update($cycleDateDataArr);
                            $processedIds[] = $id;
                        }
                    } else {
                        // Create new
                        $cycle_date_data = CycleDateData::create($cycleDateDataArr);
                        $processedIds[] = $cycle_date_data->id;
                    }

                    // Upload image if exists for this index
                    if ($request->hasFile('cycle_date_data_text_message_image') &&
                        isset($request->file('cycle_date_data_text_message_image')[$index])) {
                        
                        uploadMediaFile(
                            $cycle_date_data,
                            $request->file('cycle_date_data_text_message_image')[$index],
                            'cycle_date_data_text_message_image'
                        );
                    }
                }
            }

            // Delete unprocessed old records
            $toDeleteIds = array_diff($existingIds, $processedIds);
            if (!empty($toDeleteIds)) {
                CycleDateData::whereIn('id', $toDeleteIds)->delete();
            }
        }

        $message = __('message.update_form', ['form' => __('message.cycle_dates')]);
        return redirect()->route('cycle-dates.index')->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CycleDatesDataDataTable $dataTable ,$id)
    {
        if (!Auth::user()->can('cycledays-show')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $data = CycleDates::findOrFail($id);
        $pageTitle = __('message.cycle_day') . ' ' . $data->day;

        return $dataTable->with(['cycle_date_id' => $id])->render('cycle-dates.data-show', compact('pageTitle','data', 'id'));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('cycle-dates.index')->withErrors($message);
        }

        if (!Auth::user()->can('cycledays-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $cycle_dates_data = CycleDates::find($id);            
        if($cycle_dates_data != '') {
            $cycle_dates_data->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.cycle_dates')]);
        }
    
        if(request()->ajax()) {
            return response()->json(['status' => true, 'message' => $message ]);
        }

        return redirect()->back()->with($status,$message);
    }

    public function action(Request $request)
    {  
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('cycle-dates.index')->withErrors($message);
        }
        if (!Auth::user()->can('cycledays-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $id = $request->id;
        $cycleDate = CycleDates::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.cycle_dates')]);
        if ($request->type === 'restore') {
            $cycleDate->restore();
            $message = __('message.msg_restored', ['name' => __('message.cycle_dates')]);
        }

        if ($request->type === 'forcedelete') {
            $cycleDate->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.cycle_dates')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('cycle-dates.index')->withSuccess($message);
    }

    public function filter()
    {
        $pageTitle =  __('message.filter');

        $params = [
            'goal_type' => request('goal_type') ?? null,
            'view_type' => request('view_type') ?? null,
            'day' => request('day') ?? null,
        ];
        return view('cycle-dates.filter', compact('pageTitle','params'));
    }
}
