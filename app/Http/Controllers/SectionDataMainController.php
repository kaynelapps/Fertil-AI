<?php

namespace App\Http\Controllers;

use App\DataTables\SectionDataDataTable;
use App\DataTables\SectionDataMainDataTable;
use App\Http\Requests\SectionDataMainRequest;
use App\Imports\SelfCareImport;
use App\Models\Category;
use App\Models\SectionData;
use App\Models\SectionDataMain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SectionDataMainController extends Controller
{
    public function index(SectionDataMainDataTable $dataTable)
    {
        if (!Auth::user()->can('sections-data-main-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $model = SectionDataMain::withTrashed()->where('is_show_insights',1)->orderBy('dragondrop','asc');

        if (request()->has('goal_type') && request('goal_type') !== '') {
            $model = $model->where('goal_type', request('goal_type'));
        }
        if (request()->has('category_id') && request('category_id') !== '') {
            $model = $model->where('category_id', request('category_id'));
        }

        $sectionData = $model->get();

        $params = [
            'goal_type' => request('goal_type') ?? null,
            'category_id' => request('category_id') ?? null,
        ];
        $pageTitle = __('message.list_form_title', ['form' => __('message.self_care')]);
        $auth_user = authSession();
        $assets = ['datatable'];
        $delete_checkbox_checkout = $auth_user->can('sections-data-main-delete') ? '<button id="deleteSelectedBtn" checked-title = "sections-data-main-checked " class="float-left btn btn-sm ">' . __('message.delete_selected') . '</button>' : '';
        $button = $auth_user->can('sections-data-main-add') ? '<a href="'.route('section-data-main.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.self_care')]).'</a>' : '';
        $export = $auth_user->can('sections-data-main-add') ? '<a href="'.url('export-selfcare').'" class="float-right btn btn-sm btn-info mr-2"><i class="fa fa-download"></i> '. __('message.export').'</a>' : '';
        return $dataTable->render('sections-data.datatable', compact('pageTitle', 'button', 'auth_user', 'delete_checkbox_checkout','sectionData','export','params'));
    }

    public function topicIndex(SectionDataMainDataTable $dataTable)
    {
        if (!Auth::user()->can('sections-data-main-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $param = [
            'goal_type' => request('goal_type') ?? null,
            'category_id' => request('category_id') ?? null
        ];
        $pageTitle = __('message.list_form_title', ['form' => __('message.topic')]);
        $auth_user = authSession();
        $assets = ['datatable'];
        $delete_checkbox_checkout = $auth_user->can('sections-data-main-delete') ? '<button id="deleteSelectedBtn" checked-title = "sections-data-main-checked " class="float-left btn btn-sm ">' . __('message.delete_selected') . '</button>' : '';
        $button = $auth_user->can('sections-data-main-add') ? '<a href="'.route('section-data-main.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.topic')]).'</a>' : '';
        $filter = $auth_user->can('sections-data-main-add') ? '<a href="'.route('topic.filter',$param).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle', 'button', 'auth_user', 'delete_checkbox_checkout','filter'));
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
        return view('sections-data.filter', compact('pageTitle','params'));
    }

    public function filterSection()
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
        return view('sections-data.filterSelfcare', compact('pageTitle','params'));
    }

    public function sectionFilter($id)
    {
        $pageTitle =  __('message.filter');

        $params = [
            'view_type' => request('view_type') ?? null,
        ];
        return view('section-data-main.filter', compact('pageTitle','id','params'));
    }

    public function topicViewFilter($id)
    {
         $pageTitle =  __('message.filter');

        $params = [
            'view_type' => request('view_type') ?? null,
        ];
        return view('sections-data.topic-view-filter', compact('pageTitle','id','params'));
    }

    public function create()
    {
        $pageTitle = __('message.add_form_title', ['form' => __('message.self_care')]);

        return view('sections-data.main-form', compact('pageTitle'));
    }

    public function store(SectionDataMainRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }

        if (!Auth::user()->can('sections-data-main-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $sectiondata = SectionDataMain::max('dragondrop');
        $request['dragondrop'] = $sectiondata + 1;
        $data = $request->all();
        $section_data = SectionDataMain::create($data);

        $message = __('message.save_form', ['form' => __('message.section_data')]);

        if($section_data->is_show_insights == 0){
            return redirect()->route('topic.index')->withSuccess($message);
        }

        return redirect()->route('section-data-main.index')->withSuccess($message);
    }

    public function show(SectionDataDataTable $dataTable, $id)
    {
        if (!Auth::user()->can('sections-data-main-show')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $auth_user = authSession();
        $delete_checkbox_checkout = '<button id="deleteSelectedBtn" checked-title = "sections-data-checked " class="float-left btn btn-sm ">' . __('message.delete_selected') . '</button>' ;
        $data = SectionDataMain::findOrFail($id);
        $pageTitle = ucwords($data->title);
            $params = [
                'view_type' => request('view_type') ?? null,
            ];

        return $dataTable->with(['section_id' => $id])->render('sections-data.show', compact('pageTitle','data', 'id','delete_checkbox_checkout','params'));
    }

    public function edit($id)
    {
        if (!Auth::user()->can('sections-data-main-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.update_form_title', ['form' => __('message.self_care')]);
        $data = SectionDataMain::findOrFail($id);

        return view('sections-data.main-form', compact('data', 'pageTitle', 'id'));
    }

    public function update(SectionDataMainRequest $request, $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }

        if (!Auth::user()->can('sections-data-main-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $section_data_main = SectionDataMain::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.section_data')]);
        if ($section_data_main == null) {
            return response()->json(['status' => false, 'message' => $message]);
        }

        // sec$section_data_main data...
        $section_data_main->fill($request->all())->update();

        $message = __('message.update_form', ['form' => __('message.section_data')]);

        if (request()->is('api/*')) {
            return response()->json(['status' => (($section_data_main != '') ? true : false), 'message' => $message]);
        }

        if (auth()->check()) {
            if($section_data_main->is_show_insights == 0){
            return redirect()->route('topic.index')->withSuccess($message);
            }
            return redirect()->route('section-data-main.index')->withSuccess($message);
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

        if (!Auth::user()->can('sections-data-main-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $section_data_main = SectionDataMain::find($id);
        if ($section_data_main != '') {
            $section_data = SectionData::where('main_title_id',$section_data_main->id);
            $section_data->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.section_data')]);
        }
        $section_data_main->delete();

        if (request()->is('api/*')) {
            return response()->json(['status' => (($section_data_main != '') ? true : false), 'message' => $message]);
        }

        if (request()->ajax()) {
            return response()->json(['status' => true, 'message' => $message]);
        }

        return redirect()->back()->with($status, $message);
    }

    public function action(Request $request ,$id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }
        
        if (!Auth::user()->can('sections-data-main-restore')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $id = $request->id;
        $section_data_main = SectionDataMain::withTrashed()->where('id',$id)->first();
        $section_data = SectionData::withTrashed()->where('main_title_id',$id);

        $message = __('message.not_found_entry',['name' => __('message.section_data') ]);
        if($request->type === 'restore'){
            $section_data_main->restore();
            $section_data->restore();
            $message = __('message.msg_restored',['name' => __('message.section_data') ]);
        }

        if($request->type === 'forcedelete'){
            $section_data_main->forceDelete();
            $section_data->forceDelete();
            $message = __('message.msg_forcedelete',['name' => __('message.section_data') ]);
        }

        return redirect()->route('section-data-main.index')->withSuccess($message);
    }

    public function saveDragondrop(Request $request)
    {
        $reorderedData = $request->input('reorderedData');

        foreach ($reorderedData as $key => $data) {
            SectionDataMain::where('id', $data['id'])->update(['dragondrop' => $data['newPosition']]);
        }
        return response()->json([
            'status' => 'success',
            'message' =>  __('message.row_dragondrop')
        ]);
    }

    public function import(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('section-data-main.index')->withErrors($message);
        }
        Excel::import(new SelfCareImport, $request->file('file')->store('files'));

        $message = __('message.import_form_title',['form' => __('message.self_care')] );
        return back()->withSuccess($message);
    }

    public function importFile()
    {
        $auth_user = authSession();
        if (!auth()->user()->can('sections-data-main-list')) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }
        $pageTitle = __('message.import_data');

        return view('section-data-main.import', compact(['pageTitle']));
    }

    public function templateExcel()
    {
        $file = public_path("exportselfcare.xlsx");
        return response()->download($file);
    }

    public function help()
    {
        $pageTitle = __('message.import_details');

        return view('section-data-main.help', compact(['pageTitle']));
    }

}
