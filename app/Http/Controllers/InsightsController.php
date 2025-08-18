<?php

namespace App\Http\Controllers;

use App\DataTables\InsightsDataTable;
use App\Http\Requests\InsightsRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Insights;
use App\Models\SubSymptoms;
use App\Models\Tags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class InsightsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(InsightsDataTable $dataTable)
    {
        if (!Auth::user()->can('insights-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $params = [
            'goal_type' => request('goal_type') ?? null,
            'view_type' => request('view_type') ?? null,
            'sub_symptoms_id' => request('sub_symptoms_id') ?? null,
            'insights_type' => request('insights_type') ?? null,
        ];
        $pageTitle = __('message.list_form_title',['form' => __('message.insights')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $delete_checkbox_checkout = $auth_user->can('insights-delete') ? '<button id="deleteSelectedBtn" checked-title = "insights-checked" class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('insights-add') ? '<a href="'.route('insights.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.insights')]).'</a>' : '';
        $filter = $auth_user->can('insights-add') ? '<a href="'.route('insights.filter',$params).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('insights-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.add_form_title',[ 'form' => __('message.insights')]);

        return view('insights.form', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InsightsRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('insights.index')->withErrors($message);
        }
        if (!Auth::user()->can('insights-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

         $insight_data = [];

        foreach ($request->title_name as $key => $title) {
            $insight_data[] = [
                'title_name'  => $title,
                'description' => $request->description[$key],
                'bg_color'    => $request->bg_color[$key],
                'text_color'  => $request->text_color[$key],
            ];
        }
        $data = $request->all();
        $data['insights_data'] = json_encode($insight_data);
        if($request->view_type == 2) {
            $data['category_id'] = $request->category_id;
        } else {
            $data['category_id'] = NULL;
        }

        $insights = Insights::create($data);

        if ($insights->view_type == 3) {
            uploadMediaFile($insights,$request->image_video_image, 'image_video_image');
            uploadMediaFile($insights,$request->video_image_video, 'video_image_video');
        }
        uploadMediaFile($insights,$request->thumbnail_image, 'thumbnail_image');

        if ($request->hasFile('story_image')) {
            foreach ($request->file('story_image') as $image) {
                $insights->addMedia($image)->toMediaCollection('story_image');
            }
        }

        $message = __('message.save_form', ['form' => __('message.insights')]);

        return redirect()->route('insights.index')->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pageTitle =  __('message.insights');
        $data = Insights::findOrFail($id);

        if($data->article_id){
            $dataArticle = Article::findOrFail($data->article_id);
            $selected_tags = [];
            if(isset($dataArticle->tags_id)){
                $selected_tags = Tags::whereIn('id', $dataArticle->tags_id)->get()->mapWithKeys(function ($item) {
                    return [ $item->id => $item->name ];
                })->implode(',');
            }
        }else{
            $dataArticle = null;
            $selected_tags = null;
        }
        $dataCategory = null;
        if($data->category_id){
            $dataCategory = Category::findOrFail($data->category_id);
        }

        $videos = Media::where('model_type', 'App\Models\VideosUpload')->where('collection_name', 'videos_upload')->where('model_id', $data->video_id)->first();

        return view('insights.show', compact('data', 'pageTitle', 'id','dataArticle','selected_tags','dataCategory','videos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('insights-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $videos = Media::where('model_type', 'App\Models\VideosUpload')
              ->where('collection_name', 'videos_upload')
              ->pluck('name','id')
              ->toArray();

        $videosList = [];

        foreach ($videos as $mediaId => $name) {
            $videoUpload = \App\Models\VideosUpload::whereHas('media', function ($query) use ($mediaId) {
                $query->where('id', $mediaId);
            })->first();

            if ($videoUpload) {
                $videosList[$mediaId] = $videoUpload->title;
            }
        }
        $pageTitle = __('message.update_form_title',[ 'form' => __('message.insights')]);
        $data = Insights::findOrFail($id);
        $data->insights_data = json_decode($data->insights_data, true);

        return view('insights.form', compact('data', 'pageTitle', 'id','videosList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('insights.index')->withErrors($message);
        }

        if (!Auth::user()->can('insights-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $insights = Insights::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.insights')]);
        if($insights == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }
        $insight_data = [];

        foreach ($request->title_name as $key => $title) {
            $insight_data[] = [
                'title_name'  => $title,
                'description' => $request->description[$key],
                'bg_color'    => $request->bg_color[$key],
                'text_color'  => $request->text_color[$key],
            ];
        }
        $data = $request->all();
         $data['insights_data'] = json_encode($insight_data);
        $insights->fill($data)->update();
        if ($insights->view_type == 1 || $insights->view_type == 2) {
            $insights->clearMediaCollection('story_image');
        }

        if ($insights->view_type == 3) {
            if (isset($request->image_video_image) && $request->image_video_image != null) {
                $insights->clearMediaCollection('image_video_image');
                $insights->clearMediaCollection('story_image');
                $insights->addMediaFromRequest('image_video_image')->toMediaCollection('image_video_image');
            }
            if (isset($request->video_image_video) && $request->video_image_video != null) {
                $insights->clearMediaCollection('video_image_video');
                $insights->clearMediaCollection('story_image');
                $insights->addMediaFromRequest('video_image_video')->toMediaCollection('video_image_video');
            }
        }else {
            $insights->clearMediaCollection('video_image_video');
            $insights->clearMediaCollection('image_video_image');
        }

        if (isset($request->thumbnail_image) && $request->thumbnail_image != null) {
            $insights->clearMediaCollection('thumbnail_image');
            $insights->addMediaFromRequest('thumbnail_image')->toMediaCollection('thumbnail_image');
        }

        if ($request->hasFile('story_image')) {
            foreach ($request->file('story_image') as $image) {
                $insights->addMedia($image)->toMediaCollection('story_image');
            }
        }

        $message = __('message.update_form',['form' => __('message.insights')]);

        if(auth()->check()){
            return redirect()->route('insights.index')->withSuccess($message);
        }
        return redirect()->back()->withSuccess($message);
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
            return redirect()->route('insights.index')->withErrors($message);
        }

       if (!Auth::user()->can('insights-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $insights = Insights::find($id);
        $status = 'error';
        $message = __('message.not_found_entry', ['name' => __('message.insights')]);

        if($insights != '') {
            $insights->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.insights')]);
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
            return redirect()->route('insights.index')->withErrors($message);
        }
        
        if (!Auth::user()->can('insights-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $id = $request->id;
        $insights = Insights::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.insights')]);
        if ($request->type === 'restore') {
            $insights->restore();
            $message = __('message.msg_restored', ['name' => __('message.insights')]);
        }

        if ($request->type === 'forcedelete') {
            $insights->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.insights')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('insights.index')->withSuccess($message);
    }

    public function needHelp()
    {
        $pageTitle =  __('message.insights_needhelp');

        return view('insights.needhelp', compact('pageTitle'));
    }

    public function filter()
    {
        $pageTitle =  __('message.filter');
        $sub_symptoms_id = request('sub_symptoms_id');
        $subSymptomsName = null;

        if ($sub_symptoms_id) {
            $subsymptoms = SubSymptoms::find($sub_symptoms_id);
            if ($subsymptoms) {
                $subSymptomsName = $subsymptoms->title;
            }
        }

        $params = [
            'goal_type' => request('goal_type') ?? null,
            'sub_symptoms_id' => request('sub_symptoms_id') ?? null,
            'subsymptom_name' => $subSymptomsName,
            'view_type' => request('view_type') ?? null,
            'insights_type' => request('insights_type') ?? null,
        ];
        return view('insights.filter', compact('pageTitle','params'));
    }
}
