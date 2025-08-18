<?php

namespace App\Http\Controllers;

use App\DataTables\SectionDataDataTable;
use App\Http\Requests\SectionDataRequest;
use App\Models\Article;
use App\Models\SectionData;
use App\Models\VideosUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SectionDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(SectionDataDataTable $dataTable)
    {
        if (!Auth::user()->can('sections-data-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        
        $pageTitle = __('message.list_form_title', ['form' => __('message.section_data')]);
        $auth_user = authSession();
        $assets = ['datatable'];
        $delete_checkbox_checkout = $auth_user->can('sections-data-delete') ? '<button id="deleteSelectedBtn" checked-title = "sections-data-checked " class="float-left btn btn-sm ">' . __('message.delete_selected') . '</button>' : '';
        $button = $auth_user->can('sections-data-add') ? '<a href="'.route('section-data.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.sections')]).'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle', 'button', 'auth_user', 'delete_checkbox_checkout'));
    }


    public function create()
    {
        if (!Auth::user()->can('sections-data-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        
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

        $main_section_category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

        $pageTitle = __('message.add_form_title', ['form' => __('message.self_care_data')]);

        return view('sections-data.form', compact('pageTitle','videosList','videosCourseList','main_section_category_id'));
    }

    public function store(SectionDataRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }

        if (!Auth::user()->can('sections-data-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $data = $request->all();
        if(isset($request->category_id)) {
            $data['category_id'] = $request->category_id;
        } else {
            $data['category_id'] = $request->request_category_id;
        }

        $section_data = SectionData::create($data);

        uploadMediaFile($section_data, $request->section_data_image, 'section_data_image');
        uploadMediaFile($section_data, $request->section_data_podcast, 'section_data_podcast');

        if ($request->hasFile('section_data_story_image')) {
            foreach ($request->file('section_data_story_image') as $image) {
                $section_data->addMedia($image)->toMediaCollection('section_data_story_image');
            }
        }

        $message = __('message.save_form', ['form' => __('message.section_data')]);

        return redirect()->route('section-data-main.show',$request->main_title_id)->withSuccess($message);
    }

    public function edit($id)
    {
        if (!Auth::user()->can('sections-data-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.update_form_title', ['form' => __('message.section_data')]);
        $data = SectionData::findOrFail($id);
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
        $main_section_category_id = request('section_data_main_category_id');
        return view('sections-data.form', compact('data', 'pageTitle', 'id','selected_blog_course_article','selected_blog_article','selected_article','videosList','videosCourseList','main_section_category_id'));
    }

    public function update(SectionDataRequest $request, $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }

        if (!Auth::user()->can('sections-data-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $section_data = SectionData::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.section_data')]);
        if ($section_data == null) {
            return response()->json(['status' => false, 'message' => $message]);
        }

        // sectin$section_data data...
        $section_data->fill($request->all())->update();

        if (isset($request->section_data_image) && $request->section_data_image != null) {
            $section_data->clearMediaCollection('section_data_image');
            $section_data->addMediaFromRequest('section_data_image')->toMediaCollection('section_data_image');
        }

        if (isset($request->section_data_podcast) && $request->section_data_podcast != null) {
            $section_data->clearMediaCollection('section_data_podcast');
            $section_data->addMediaFromRequest('section_data_podcast')->toMediaCollection('section_data_podcast');
        }

        if ($request->hasFile('section_data_story_image')) {
            foreach ($request->file('section_data_story_image') as $image) {
                $section_data->addMedia($image)->toMediaCollection('section_data_story_image');
            }
        }

        $message = __('message.update_form', ['form' => __('message.section_data')]);

        if (request()->is('api/*')) {
            return response()->json(['status' => (($section_data != '') ? true : false), 'message' => $message]);
        }

        if (auth()->check()) {
            return redirect()->route('section-data-main.show',$request->main_title_id)->withSuccess($message);
        }
        return redirect()->back()->withSuccess($message);
    }

    public function destroy($id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }

        if (!Auth::user()->can('sections-data-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $section_data = SectionData::find($id);
        if ($section_data != '') {
            $section_data->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.section_data')]);
        }

        if (request()->is('api/*')) {
            return response()->json(['status' => (($section_data != '') ? true : false), 'message' => $message]);
        }

        if (request()->ajax()) {
            return response()->json(['status' => true, 'message' => $message]);
        }

        return redirect()->back()->with($status, $message);
    }

    public function action(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }
        
        $id = $request->id;
        $section_data = SectionData::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.section_data')]);
        if ($request->type === 'restore') {
            $section_data->restore();
            $message = __('message.msg_restored', ['name' => __('message.section_data')]);
        }

        if ($request->type === 'forcedelete') {
            $section_data->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.section_data')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->back()->withSuccess($message);
    }
}
