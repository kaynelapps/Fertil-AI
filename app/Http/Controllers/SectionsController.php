<?php

namespace App\Http\Controllers;

use App\DataTables\SectionsDataTable;
use App\Http\Requests\SectionsRequest;
use App\Imports\InfoSectionImport;
use App\Models\Category;
use App\Models\Sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SectionsDataTable $dataTable)
    {
        if (!Auth::user()->can('sections-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.list_form_title',['form' => __('message.sections')] );
        $auth_user = authSession();
        $param = [ 
            'goal_type' => request('goal_type') ?? null,
            'category_id' => request('category_id') ?? null
        ];
        $assets = ['datatable'];
        $delete_checkbox_checkout = $auth_user->can('sections-delete') ? '<button id="deleteSelectedBtn" checked-title = "sections-checked" class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('sections-add') ? '<a href="'.route('sections.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.sections')]).'</a>' : '';
        $export = $auth_user->can('sections-add') ? '<a href="'.url('export-sections').'" class="float-right btn btn-sm btn-info ml-2"><i class="fa fa-download"></i> '. __('message.export').'</a>' : '';
        $import = $auth_user->can('sections-add') ? '<a href="'.route('bulk.infosection').'" class="float-right btn btn-sm btn-primary ml-2"><i class="fas fa-file-import"></i> '. __('message.import').'</a>' : '';
        $filter = $auth_user->can('sections-add') ? '<a href="'.route('sections.filter',$param).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>' : '';
        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','export','import','filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('sections-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        
        $pageTitle = __('message.add_form_title',[ 'form' => __('message.info_section')]);
        
        return view('sections.form', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectionsRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('sections.index')->withErrors($message);
        }
        if (!Auth::user()->can('sections-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $data = $request->all();
        $sections = Sections::create($data);

        uploadMediaFile($sections,$request->info_section_image, 'info_section_image');

        $message = __('message.save_form', ['form' => __('message.sections')]);        

        return redirect()->route('sections.index')->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Sections::find($id);
       return view('sections.view',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('sections-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.update_form_title',[ 'form' => __('message.sections')]);
        $data = Sections::findOrFail($id);
        
        return view('sections.form', compact('data', 'pageTitle', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SectionsRequest $request, $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('sections.index')->withErrors($message);
        }

        if (!Auth::user()->can('sections-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $section = Sections::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.sections')]);
        if($section == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }

        $section->fill($request->all())->update();

        if (isset($request->info_section_image) && $request->info_section_image != null) {
            $section->clearMediaCollection('info_section_image');
            $section->addMediaFromRequest('info_section_image')->toMediaCollection('info_section_image');
        }

        $message = __('message.update_form',['form' => __('message.sections')]);

        if(request()->is('api/*')){
            return response()->json(['status' =>  (($section != '') ? true : false) , 'message' => $message ]);
        }

        if(auth()->check()){
            return redirect()->route('sections.index')->withSuccess($message);
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
            return redirect()->route('sections.index')->withErrors($message);
        }

        if (!Auth::user()->can('sections-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $section = Sections::find($id);            
        if($section != '') {
            $section->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.sections')]);
        }
        
        if(request()->is('api/*')){
            return response()->json(['status' =>  (($section != '') ? true : false) , 'message' => $message ]);
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
            return redirect()->route('sections.index')->withErrors($message);
        }

        if (!Auth::user()->can('sections-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
    
        $id = $request->id;
        $section = Sections::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.sections')]);
        if ($request->type === 'restore') {
            $section->restore();
            $message = __('message.msg_restored', ['name' => __('message.sections')]);
        }

        if ($request->type === 'forcedelete') {
            $section->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.sections')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('sections.index')->withSuccess($message);
    }

    public function importFile()
    {
        $auth_user = authSession();
        if (!auth()->user()->can('sections-list')) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }
        $pageTitle = __('message.import_data');

        return view('sections.import', compact(['pageTitle']));
    }

    public function import(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('sections.index')->withErrors($message);
        }
        
        Excel::import(new InfoSectionImport, $request->file('file')->store('files'));

         $message = __('message.import_form_title',['form' => __('message.sections')] );
        return back()->withSuccess($message);
    }

    public function templateExcel()
    {
        $file = public_path("infosection.xlsx");
        return response()->download($file);
    }

    public function help()
    {
        $pageTitle = __('message.import_details');

        return view('sections.help', compact(['pageTitle']));   
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
        return view('sections.filter', compact('pageTitle','params'));
    }

}
