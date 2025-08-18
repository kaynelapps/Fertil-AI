<?php

namespace App\Http\Controllers;

use App\DataTables\VideosUploadDataTable;
use App\Http\Requests\VideoUploadRequest;
use App\Models\VideosUpload;
use Illuminate\Http\Request;

class VideosUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VideosUploadDataTable $dataTable)
    {
        $pageTitle = __('message.list_form_title',['form' => __('message.videos_list')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $delete_checkbox_checkout = $auth_user->can('uploadvideos-delete') ? '<button id="deleteSelectedBtn" checked-title = "video-upload-checked " class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('uploadvideos-add') ? '<a href="'.route('upload-videos.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.video')]).'</a>' : '';
        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = __('message.add_form_title',[ 'form' => __('message.videos_list')]);
        
        return view('upload-videos.form', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VideoUploadRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('upload-videos.index')->withErrors($message);
        }

        $data = $request->all();
        $video_data = VideosUpload::create($data);

        uploadMediaFile($video_data,$request->upload_video_thumbnail_image, 'upload_video_thumbnail_image');
        uploadMediaFile($video_data,$request->videos_upload, 'videos_upload');

        $message = __('message.save_form', ['form' => __('message.upload_videos')]);        

        return redirect()->route('upload-videos.index')->withSuccess($message);
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
        $pageTitle = __('message.update_form_title',[ 'form' => __('message.video')]);
        $data = VideosUpload::findOrFail($id);
        
        return view('upload-videos.form', compact('data', 'pageTitle', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VideoUploadRequest $request, $id)
    {

        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('upload-videos.index')->withErrors($message);
        }
        $video_data = VideosUpload::find($id);
        
        $message = __('message.not_found_entry', ['name' => __('message.upload_videos')]);
        if($video_data == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }

        $video_data->fill($request->all())->update();

        if (isset($request->upload_video_thumbnail_image) && $request->upload_video_thumbnail_image != null) {
            $video_data->clearMediaCollection('upload_video_thumbnail_image');
            $video_data->addMediaFromRequest('upload_video_thumbnail_image')->toMediaCollection('upload_video_thumbnail_image');
        }

        if ($request->hasFile('videos_upload')) {
            $video_data->clearMediaCollection('videos_upload');
            $video_data->addMediaFromRequest('videos_upload')->toMediaCollection('videos_upload');
        }

        $message = __('message.update_form',['form' => __('message.upload_videos')]);

        if(auth()->check()){
            return redirect()->route('upload-videos.index')->withSuccess($message);
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
            return redirect()->route('upload-videos.index')->withErrors($message);
        }
        
        $video_data = VideosUpload::find($id);            
        if($video_data != '') {
            $video_data->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.upload_videos')]);
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
            return redirect()->route('upload-videos.index')->withErrors($message);
        }

        $id = $request->id;
        $video_data = VideosUpload::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.upload_videos')]);
        if ($request->type === 'restore') {
            $video_data->restore();
            $message = __('message.msg_restored', ['name' => __('message.upload_videos')]);
        }

        if ($request->type === 'forcedelete') {
            $video_data->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.upload_videos')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('upload-videos.index')->withSuccess($message);
    }
}
