<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\DataTables\CategoryDataTable;
use App\DataTables\CommonQuestionDataTable;
use App\DataTables\ImageSectionDataTable;
use App\DataTables\SectionsDataTable;
use App\Http\Requests\CategoryRequest;
use App\Imports\CategoryImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoryDataTable $dataTable)
    {
        if (!Auth::user()->can('category-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.list_form_title',['form' => __('message.category')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $param = [
            'goal_type' => request('goal_type') ?? null,
        ];
        $delete_checkbox_checkout = $auth_user->can('category-delete') ? '<button id="deleteSelectedBtn" checked-title = "category-checked" class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('category-add') ? '<a href="'.route('category.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.category')]).'</a>' : '';
        $export = $auth_user->can('category-add') ? '<a href="'.url('export-categories').'" class="float-right btn btn-sm btn-info ml-2"><i class="fa fa-download"></i> '. __('message.export').'</a>' : '';
        $import = $auth_user->can('category-add') ? '<a href="'.route('bulk.data').'" class="float-right btn btn-sm btn-primary ml-2"><i class="fas fa-file-import"></i> '. __('message.import').'</a>' : '';
        $filter = $auth_user->can('category-add') ? '<a href="'.route('category.filter',$param).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','export','import','filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('category-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.add_form_title',[ 'form' => __('message.category')]);

        return view('category.form', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('category.index')->withErrors($message);
        }

        if (!Auth::user()->can('category-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $data = $request->all();
        $category = Category::create($data);

        uploadMediaFile($category,$request->category_thumbnail_image, 'category_thumbnail_image');
        uploadMediaFile($category,$request->header_image, 'header_image');

        $message = __('message.save_form', ['form' => __('message.category')]);

        return redirect()->route('category.index')->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SectionsDataTable $infosectionssataTable,CommonQuestionDataTable $commonquestiondatatable,ImageSectionDataTable $imagedectiondatatable,$id)
    {
        if (!Auth::user()->can('category-show')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $data = Category::with('section_data_main','section_data_main.section_data')->findOrFail($id);
        $type = request('type') ?? 'detail';

        switch ($type) {
            case 'image_section':
                 return view('category.show', compact('data','id','type'));
                break;

            case 'info_section':
                return $infosectionssataTable->with(['view_category_id' => $id , 'category_view_type' => 'category_view_info_section'])->render('category.show', compact('type', 'data','id'));
                break;

            case 'common_question_answers':
                return $commonquestiondatatable->with(['view_category_id' => $id , 'category_view_type' => 'category_view_common_question'])->render('category.show', compact('type', 'data','id'));
                break;

            default:
                # code...
                break;
            }

        return $imagedectiondatatable->with(['view_category_id' => $id , 'category_view_type' => 'category_view_image_section'])->render('category.show', compact('type', 'data','id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('category-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.update_form_title',[ 'form' => __('message.category')]);
        $data = Category::findOrFail($id);

        return view('category.form', compact('data', 'pageTitle', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        if (!Auth::user()->can('category-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('category.index')->withErrors($message);
        }

        $category = Category::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.category')]);
        if($category == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }

        // Category data...
        $category->fill($request->all())->update();

        if (isset($request->category_thumbnail_image) && $request->category_thumbnail_image != null) {
            $category->clearMediaCollection('category_thumbnail_image');
            $category->addMediaFromRequest('category_thumbnail_image')->toMediaCollection('category_thumbnail_image');
        }

        if (isset($request->header_image) && $request->header_image != null) {
            $category->clearMediaCollection('header_image');
            $category->addMediaFromRequest('header_image')->toMediaCollection('header_image');
        }

        $message = __('message.update_form',['form' => __('message.category')]);

        if(request()->is('api/*')){
            return response()->json(['status' =>  (($category != '') ? true : false) , 'message' => $message ]);
        }

        if(auth()->check()){
            return redirect()->route('category.index')->withSuccess($message);
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
        if (!Auth::user()->can('category-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('category.index')->withErrors($message);
        }

        $category = Category::find($id);
        if($category != '') {
            $category->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.category')]);
        }

        if(request()->is('api/*')){
            return response()->json(['status' =>  (($category != '') ? true : false) , 'message' => $message ]);
        }

        if(request()->ajax()) {
            return response()->json(['status' => true, 'message' => $message ]);
        }

        return redirect()->back()->with($status,$message);
    }
    public function action(CategoryRequest $request)
    {
        if (!Auth::user()->can('category-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

         if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('category.index')->withErrors($message);
        }

        $id = $request->id;
        $category = Category::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.category')]);
        if ($request->type === 'restore') {
            $category->restore();
            $message = __('message.msg_restored', ['name' => __('message.category')]);
        }

        if ($request->type === 'forcedelete') {
            $category->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.category')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('category.index')->withSuccess($message);
    }


    public function needHelp()
    {
        $pageTitle =  __('message.category_needhelp');

        return view('category.needhelp', compact('pageTitle'));
    }

    public function selfcareNeedHhelp()
    {
        $pageTitle =  __('message.category_needhelp');

        return view('category.selfcare-needhelp', compact('pageTitle'));
    }

    public function import(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('category.index')->withErrors($message);
        }
        Excel::import(new CategoryImport, $request->file('file')->store('files'));

        $message = __('message.import_form_title',['form' => __('message.category')] );
        return back()->withSuccess($message);
    }

    public function importFile()
    {
        $auth_user = authSession();
        if (!auth()->user()->can('category-list')) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }
        $pageTitle = __('message.import_data');

        return view('category.import', compact(['pageTitle']));
    }

    public function templateExcel()
    {
        $file = public_path("exportfile.xlsx");
        return response()->download($file);
    }

    public function help()
    {
        $pageTitle = __('message.import_details');

        return view('category.help', compact(['pageTitle']));
    }

    public function filter()
    {
        $pageTitle =  __('message.filter');
        $params = [
            'goal_type' => request('goal_type') ?? null,
        ];
        return view('category.filter', compact('pageTitle','params'));
    }
}

