<?php

namespace App\Http\Controllers;

use App\DataTables\PersonalisedInsightsDataTable;
use App\Models\PersonalisedInsights;
use App\Models\User;
use App\Models\VideosUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PersonalisedInsightsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PersonalisedInsightsDataTable $dataTable)
    {
        if (!Auth::user()->can('personalinsights-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $params = [
            'goal_type' => request('goal_type') ?? null,
            'insights_type' => request('insights_type') ?? null,
            'view_type' => request('view_type') ?? null,
        ];
        $pageTitle = __('message.list_form_title',['form' => __('message.personalinsights')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $delete_checkbox_checkout = $auth_user->can('personalinsights-delete') ? '<button id="deleteSelectedBtn" checked-title = "personalinsights-checked" class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('personalinsights-add') ? '<a href="'.route('personalinsights.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.personalinsights')]).'</a>' : '';
        $filter = $auth_user->can('personalinsights-add') ? '<a href="'.route('personalinsights.filter',$params).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('personalinsights-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.add_form_title',[ 'form' => __('message.personalinsights')]);

        $relation = [
            'client' => User::where('user_type', 'app_user')->where('status', 'active')->get()->pluck('display_name', 'id'),
            'anonymous_user' => User::where('user_type', 'anonymous_user')->where('status', 'active')->get()->pluck('display_name', 'id'),
        ];

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
        return view('personalinsights.form', compact('pageTitle','videosList','videosCourseList') + $relation);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!Auth::user()->can('personalinsights-add')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('personalinsights.index')->withErrors($message);
        }

        $personalinsight_data = [];
        foreach ($request->title_name as $key => $title) {
            $personalinsight_data[] = [
                'title_name'  => $title,
                'description' => $request->description[$key],
                'bg_color'    => $request->bg_color[$key],
                'text_color'  => $request->text_color[$key],
            ];
        }

        $data = $request->all();
          $data['personalinsights_data'] = json_encode($personalinsight_data);
        if($request->view_type == 2) {
            $data['category_id'] = $request->category_id;
        } else {
            $data['category_id'] = NULL;
        }
        $data['users'] =  json_encode($request->users);
        $data['anonymous_user'] =  json_encode($request->anonymous_user);
        $personalinsights = PersonalisedInsights::create($data);

        if ($personalinsights->view_type == 3) {
            uploadMediaFile($personalinsights,$request->image_video_image, 'image_video_image');
            uploadMediaFile($personalinsights,$request->video_image_video, 'video_image_video');
        }
        uploadMediaFile($personalinsights,$request->thumbnail_image, 'thumbnail_image');

        if ($request->hasFile('story_image')) {
            foreach ($request->file('story_image') as $image) {
                $personalinsights->addMedia($image)->toMediaCollection('story_image');
            }
        }

        $message = __('message.save_form', ['form' => __('message.personalinsights')]);

        return redirect()->route('personalinsights.index')->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('personalinsights-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $videos = Media::where('model_type', 'App\Models\VideosUpload')
              ->where('collection_name', 'videos_upload')
              ->pluck('name','id')
              ->toArray();

        $videosList = [];
        $relation = [
            'client' => User::where('user_type', 'app_user')->where('status', 'active')->get()->pluck('display_name', 'id'),
            'anonymous_user' => User::where('user_type', 'anonymous_user')->where('status', 'active')->get()->pluck('display_name', 'id'),
        ];

        foreach ($videos as $mediaId => $name) {
            $videoUpload = \App\Models\VideosUpload::whereHas('media', function ($query) use ($mediaId) {
                $query->where('id', $mediaId);
            })->first();

            if ($videoUpload) {
                $videosList[$mediaId] = $videoUpload->title;
            }
        }

        $pageTitle = __('message.update_form_title',[ 'form' => __('message.personalinsights')]);
        $data = PersonalisedInsights::findOrFail($id);

        $selected_user = collect();
        $users = json_decode($data->users);
        if (!empty($users) && is_array($users)) {
            $selected_user = User::whereIn('id', $users)->get()->mapWithKeys(function ($item) {
                return [$item->id => $item->display_name];
            });
        }

        $selectedUserIds = $selected_user->keys()->toArray();


        $selected_anonymous = collect();
        $anonymous_user = json_decode($data->anonymous_user, true);

        if (!empty($anonymous_user) && is_array($anonymous_user)) {
            $selected_anonymous = User::whereIn('id', $anonymous_user)->get()->mapWithKeys(function ($item) {
                return [$item->id => $item->display_name];
            });
        }
        $data->personalinsights_data = json_decode($data->personalinsights_data, true);

        $selectedAnonymousIds = $selected_anonymous->keys()->toArray();


        return view('personalinsights.form', compact('data', 'pageTitle', 'id','videosList','selectedUserIds','selectedAnonymousIds') + $relation);

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
            return redirect()->route('personalinsights.index')->withErrors($message);
        }

        if (!Auth::user()->can('personalinsights-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $personalinsights = PersonalisedInsights::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.personalinsights')]);
        if($personalinsights == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }
     $personalinsight_data = [];

        foreach ($request->title_name as $key => $title) {
            $personalinsight_data[] = [
                'title_name'  => $title,
                'description' => $request->description[$key],
                'bg_color'    => $request->bg_color[$key],
                'text_color'  => $request->text_color[$key],
            ];
        }
        $request['personalinsights_data'] = json_encode($personalinsight_data);
        $request['users'] = json_encode($request->users);
        $personalinsights->fill($request->all())->update();

        if ($personalinsights->view_type == 3) {
            if (isset($request->image_video_image) && $request->image_video_image != null) {
                $personalinsights->clearMediaCollection('image_video_image');
                $personalinsights->addMediaFromRequest('image_video_image')->toMediaCollection('image_video_image');
            }
            if (isset($request->video_image_video) && $request->video_image_video != null) {
                $personalinsights->clearMediaCollection('video_image_video');
                $personalinsights->addMediaFromRequest('video_image_video')->toMediaCollection('video_image_video');
            }
        }else {
            $personalinsights->clearMediaCollection('video_image_video');
            $personalinsights->clearMediaCollection('image_video_image');
        }

        if (isset($request->thumbnail_image) && $request->thumbnail_image != null) {
            $personalinsights->clearMediaCollection('thumbnail_image');
            $personalinsights->addMediaFromRequest('thumbnail_image')->toMediaCollection('thumbnail_image');
        }

        if ($request->hasFile('story_image')) {
            foreach ($request->file('story_image') as $image) {
                $personalinsights->addMedia($image)->toMediaCollection('story_image');
            }
        }

        $message = __('message.update_form',['form' => __('message.personalinsights')]);

        if(auth()->check()){
            return redirect()->route('personalinsights.index')->withSuccess($message);
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
            return redirect()->route('personalinsights.index')->withErrors($message);
        }

       if (!Auth::user()->can('personalinsights-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $personalinsights = PersonalisedInsights::find($id);
        $status = 'error';
        $message = __('message.not_found_entry', ['name' => __('message.personalinsights')]);

        if($personalinsights != '') {
            $personalinsights->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.personalinsights')]);
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
            return redirect()->route('personalinsights.index')->withErrors($message);
        }

        if (!Auth::user()->can('personalinsights-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $id = $request->id;
        $personalinsights = PersonalisedInsights::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.personalinsights')]);
        if ($request->type === 'restore') {
            $personalinsights->restore();
            $message = __('message.msg_restored', ['name' => __('message.personalinsights')]);
        }

        if ($request->type === 'forcedelete') {
            $personalinsights->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.personalinsights')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('personalinsights.index')->withSuccess($message);
    }

    public function filter()
    {
        $pageTitle =  __('message.filter');

        $params = [
            'goal_type' => request('goal_type') ?? null,
            'insights_type' => request('insights_type') ?? null,
            'view_type' => request('view_type') ?? null,
        ];
        return view('personalinsights.filter', compact('pageTitle','params'));
    }
}
