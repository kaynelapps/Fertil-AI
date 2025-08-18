<?php

namespace App\Http\Controllers;

use App\DataTables\PregnancyWeekDataTable;
use App\Models\PregnancyWeek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PregnancyWeekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PregnancyWeekDataTable $dataTable)
    {
        if (!Auth::user()->can('pregnancyweek-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $pageTitle = __('message.list_form_title',['form' => __('message.pregnancy_date')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $params = [
            'weeks' => request('weeks') ?? null,
            'view_type' => request('view_type') ?? null,
        ];
        $delete_checkbox_checkout = $auth_user->can('pregnancyweek-delete') ? '<button id="deleteSelectedBtn" checked-title = "pregnancy-week-checked " class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('pregnancyweek-add') ? '<a href="'.route('pregnancy-week.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.pregnancy_date')]).'</a>' : '';
        $filter = $auth_user->can('pregnancyweek-add') ? '<a href="'.route('pregnancyweek.filter',$params).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = __('message.add_form_title', ['form' => __('message.pregnancy_date')]);

        return view('pregnancyweek.form', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('pregnancy-week.index')->withErrors($message);
        }
        // if (!Auth::user()->can('personalinsights-add')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        $data = $request->all();
        if($request->view_type == 2) {
            $data['category_id'] = $request->category_id;
        } else {
            $data['category_id'] = NULL;
        }
        $pregnanacyweek = PregnancyWeek::create($data);

        if ($pregnanacyweek->view_type == 3) {
            uploadMediaFile($pregnanacyweek,$request->image_video_image, 'image_video_image');
            uploadMediaFile($pregnanacyweek,$request->video_image_video, 'video_image_video');
        }
        uploadMediaFile($pregnanacyweek,$request->thumbnail_image, 'thumbnail_image');

        if ($request->hasFile('story_image')) {
            foreach ($request->file('story_image') as $image) {
                $pregnanacyweek->addMedia($image)->toMediaCollection('story_image');
            }
        }

        $message = __('message.save_form', ['form' => __('message.pregnancy_date')]);

        return redirect()->route('pregnancy-week.index')->withSuccess($message);
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

        $pageTitle = __('message.update_form_title',[ 'form' => __('message.pregnancy_date')]);
        $data = PregnancyWeek::findOrFail($id);

        return view('pregnancyweek.form', compact('data', 'pageTitle', 'id','videosList'));
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
            return redirect()->route('pregnancy-week.index')->withErrors($message);
        }

        $pregnanacyweek = PregnancyWeek::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.pregnancy_date')]);
        if($pregnanacyweek == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }
        $pregnanacyweek->fill($request->all())->update();

        if ($pregnanacyweek->view_type == 3) {
            if (isset($request->image_video_image) && $request->image_video_image != null) {
                $pregnanacyweek->clearMediaCollection('image_video_image');
                $pregnanacyweek->addMediaFromRequest('image_video_image')->toMediaCollection('image_video_image');
            }
            if (isset($request->video_image_video) && $request->video_image_video != null) {
                $pregnanacyweek->clearMediaCollection('video_image_video');
                $pregnanacyweek->addMediaFromRequest('video_image_video')->toMediaCollection('video_image_video');
            }
        }else {
            $pregnanacyweek->clearMediaCollection('video_image_video');
            $pregnanacyweek->clearMediaCollection('image_video_image');
        }

        if (isset($request->thumbnail_image) && $request->thumbnail_image != null) {
            $pregnanacyweek->clearMediaCollection('thumbnail_image');
            $pregnanacyweek->addMediaFromRequest('thumbnail_image')->toMediaCollection('thumbnail_image');
        }

        if ($request->hasFile('story_image')) {
            foreach ($request->file('story_image') as $image) {
                $pregnanacyweek->addMedia($image)->toMediaCollection('story_image');
            }
        }

        $message = __('message.update_form',['form' => __('message.pregnancy_date')]);

        if(auth()->check()){
            return redirect()->route('pregnancy-week.index')->withSuccess($message);
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
            return redirect()->route('pregnancy-week.index')->withErrors($message);
        }

        $askexpert = PregnancyWeek::find($id);
        if($askexpert != '') {
            $askexpert->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.pregnancy_date')]);
        }

        if(request()->is('api/*')){
            return response()->json(['status' =>  (($askexpert != '') ? true : false) , 'message' => $message ]);
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
            return redirect()->route('pregnancy-week.index')->withErrors($message);
        }

        $id = $request->id;
        $pregnanacyweek = PregnancyWeek::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.pregnancy_date')]);
        if ($request->type === 'restore') {
            $pregnanacyweek->restore();
            $message = __('message.msg_restored', ['name' => __('message.pregnancy_date')]);
        }

        if ($request->type === 'forcedelete') {
            $pregnanacyweek->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.pregnancy_date')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('pregnancy-week.index')->withSuccess($message);
    }

      public function filter()
    {
        $pageTitle =  __('message.filter');

        $params = [
            'weeks' => request('weeks') ?? null,
            'view_type' => request('view_type') ?? null,
        ];
        return view('pregnancy-week.filter', compact('pageTitle','params'));
    }
}
