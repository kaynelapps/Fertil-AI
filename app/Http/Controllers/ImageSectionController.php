<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\ImageSectionDataTable;
use App\Http\Requests\ImageSectionRequest;
use App\Models\Category;
use App\Models\ImageSection;

class ImageSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ImageSectionDataTable $dataTable)
    {
       
        $pageTitle = __('message.list_form_title',['form' => __('message.image_section')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $params = [ 
            'goal_type' => request('goal_type') ?? null,
        ];
        $delete_checkbox_checkout = $auth_user->can('imagesection-delete') ? '<button id="deleteSelectedBtn" checked-title = "image-section-checked " class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('imagesection-add') ? '<a href="'.route('image-section.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.image_section')]).'</a>' : '';
        $filter = $auth_user->can('imagesection-add') ? '<a href="'.route('imagesection.filter',$params).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = __('message.add_form_title', ['form' => __('message.image_section')]);

        return view('image_section.form', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImageSectionRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('image-section.index')->withErrors($message);
        }

        $data = ImageSection::create($request->all());
        uploadMediaFile($data,$request->image_section_thumbnail_image, 'image_section_thumbnail_image');
        $message = __('message.save_form', ['form' => __('message.image_section')]);

        return redirect()->route('image-section.index')->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pageTitle = __('message.add_form_title',[ 'form' => __('message.image_section')]);
        $data = ImageSection::findOrFail($id);

        return view('pregnancy_date.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = __('message.update_form_title', ['form' => __('message.image_section')]);
        $data = ImageSection::findOrFail($id);

        return view('image_section.form', compact('data', 'pageTitle', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ImageSectionRequest $request, $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('image-section.index')->withErrors($message);
        }
        $data = ImageSection::find($id);

        $message = __('message.not_found_entry', ['name' => __('message.image_section')]);
        if ($data == null) {
            return response()->json(['status' => false, 'message' => $message]);
        }

        $data->fill($request->all())->update();
        if (isset($request->image_section_thumbnail_image) && $request->image_section_thumbnail_image != null) {
            $data->clearMediaCollection('image_section_thumbnail_image');
            $data->addMediaFromRequest('image_section_thumbnail_image')->toMediaCollection('image_section_thumbnail_image');
        }
        $message = __('message.update_form', ['form' => __('message.image_section')]);

        if (auth()->check()) {
            return redirect()->route('image-section.index')->withSuccess($message);
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
            return redirect()->route('image-section.index')->withErrors($message);
        }

        $data = ImageSection::find($id);
        $status = 'error';
        $message = __('message.not_found_entry', ['name' => __('message.image_section')]);

        if($data != '') {
            $data->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.image_section')]);
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
            return redirect()->route('image-section.index')->withErrors($message);
        }

        $id = $request->id;
        $data = ImageSection::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.image_section')]);
        if ($request->type === 'restore') {
            $data->restore();
            $message = __('message.msg_restored', ['name' => __('message.image_section')]);
        }

        if ($request->type === 'forcedelete') {
            $data->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.image_section')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('image-section.index')->withSuccess($message);
    }

    public function filter()
    {
        $pageTitle =  __('message.filter');
        
        $categoryId = request('category_id');
        $categoryName = null;
    
        if ($categoryId) {
            $category = Category::find($categoryId);
            if ($category) {
                $categoryName = $category->name;
            }
        }
        $params = [ 
            'goal_type' => request('goal_type') ?? null,
            'category_id' => request('category_id') ?? null,
            'category_name' => $categoryName

        ];
        return view('image-section.filter', compact('pageTitle','params'));
    }
}
