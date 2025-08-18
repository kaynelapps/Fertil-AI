<?php

namespace App\Http\Controllers;

use App\DataTables\CustomTopicDataTable;
use App\DataTables\SectionDataDataTable;
use App\Models\CustomTopic;
use App\Models\SectionData;
use App\Models\SectionDataMain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomTopicController extends Controller
{
    public function index(CustomTopicDataTable $dataTable)
    {
        if (!Auth::user()->can('customtopic-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.list_form_title',['form' => __('message.custom_topic')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $params = [
            'goal_type' => request('goal_type') ?? null,
        ];
        $delete_checkbox_checkout = $auth_user->can('customtopic-delete') ? '<button id="deleteSelectedBtn" checked-title = "customtopic-checked " class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('customtopic-add') ? '<a href="'.route('customtopic.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.custom_topic')]).'</a>' : '';
        $filter = $auth_user->can('customtopic-add') ? '<a href="'.route('customtopic.filter',$params).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>' : '';
        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','filter'));
    }

    public function create()
    {
        if (!Auth::user()->can('customtopic-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.add_form_title', ['form' => __('message.custom_topic')]);

        return view('customtopic.form', compact('pageTitle'));
    }

    public function search(Request $request, $id)
    {
        $data = CustomTopic::find($id);
        $pageTitle = __('message.search_topic');
        $topicIds = $data->topic_ids ? json_decode($data->topic_ids) : [];

        $query = SectionData::whereNotIn('id', $topicIds)
            ->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if( $request->view_type != '')
        {
            $query->where('view_type',$request->view_type);
        }

        $results = $query->take(10)->get();

        if ($request->ajax()) {
            $html = view('customtopic.search-results', compact('results', 'id'))->render();
            return response()->json(['html' => $html]);
        }
        $params = [
            'view_type' => request('view_type') ?? null,
        ];

        return view('customtopic.search', compact('id', 'data', 'pageTitle', 'results','params'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }

        if (!Auth::user()->can('customtopic-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $data = $request->all();
        $customtopic = CustomTopic::create($data);

        $message = __('message.save_form', ['form' => __('message.custom_topic')]);

        return redirect()->route('customtopic.index')->withSuccess($message);
    }

    public function storeIds(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }

        $customtopic = CustomTopic::find($request->id);
        $existingTopicIds = $customtopic->topic_ids ? json_decode($customtopic->topic_ids) : [];
        $newTopicIds = $request->topic_id;
        $mergedTopicIds = array_unique(array_merge($existingTopicIds, $newTopicIds));
        $customtopic->topic_ids = json_encode(array_values($mergedTopicIds));
        $customtopic->save();

        return response()->json(['status' => true, 'message' => 'Data saved successfully']);
    }

    public function customeFilter($id)
    {
        $pageTitle =  __('message.filter');

        $params = [
            'view_type' => request('view_type') ?? null,
        ];
        return view('section-data-main.filter', compact('pageTitle','id','params'));
    }

    public function show(SectionDataDataTable $dataTable, $id)
    {
        if (!Auth::user()->can('customtopic-show')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $auth_user = authSession();
        $delete_checkbox_checkout = '<button id="deleteSelectedBtn" checked-title = "sections-data-checked " class="float-left btn btn-sm ">' . __('message.delete_selected') . '</button>' ;
        $data = CustomTopic::findOrFail($id);
        $pageTitle = ucwords($data->title);
        $topicIds = $data->topic_ids ? json_decode($data->topic_ids) : [];
        $params = [
            'view_type' => request('view_type') ?? null,
         ];

        return $dataTable->with(['topic_id' => $topicIds,'custome_id' => $data->id])->render('customtopic.show', compact('pageTitle','data', 'id','delete_checkbox_checkout','params'));
    }

    public function edit($id)
    {
        $pageTitle = __('message.update_form_title', ['form' => __('message.custom_topic')]);

        $data = CustomTopic::find($id);
      return view('customtopic.form',compact('pageTitle','data','id'));

    }

    public function update(Request $request, $id)
    {
         if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }

        if (!Auth::user()->can('customtopic-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $customtopic = CustomTopic::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.custom_topic')]);
        if($customtopic == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }
        $customtopic->fill($request->all())->update();
        $message = __('message.update_form',['form' => __('message.custom_topic')]);

        if(auth()->check()){
            return redirect()->route('customtopic.index')->withSuccess($message);
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

        $customtopic = CustomTopic::find($id);
        if($customtopic != '') {
            $customtopic->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.custom_topic')]);
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
            return redirect()->back()->withErrors($message);
        }
        
        if (!Auth::user()->can('customtopic-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $id = $request->id;
        $customtopic = CustomTopic::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.custom_topic')]);
        if ($request->type === 'restore') {
            $customtopic->restore();
            $message = __('message.msg_restored', ['name' => __('message.custom_topic')]);
        }

        if ($request->type === 'forcedelete') {
            $customtopic->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.custom_topic')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('customtopic.index')->withSuccess($message);
    }

    public function topiciddestroy(Request $request, $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }
        
        $custome = CustomTopic::find($request->custome_id);

        if (!$custome) {
           $message = __('message.not_found_entry', ['name' => __('message.custom_topic')]);
        }

        $topicIds = json_decode($custome->topic_ids);


        if (($key = array_search($id, $topicIds)) !== false) {
            unset($topicIds[$key]);
        }

        $custome->topic_ids = json_encode(array_values($topicIds));
        $custome->save();

        $message = __('message.delete_successfully');

       return redirect()->back()->withSuccess($message);
    }

    public function filter()
    {
        $pageTitle =  __('message.filter');

        $params = [
            'goal_type' => request('goal_type') ?? null,
        ];
        return view('customtopic.filter', compact('pageTitle','params'));
    }

    public function customtopicFilter($id)
    {
         $pageTitle =  __('message.filter');

        $params = [
            'view_type' => request('view_type') ?? null,
        ];
        return view('customtopic.datafilter', compact('pageTitle','id','params'));
    }

    public function customtopicSearchFilter($id)
    {
        $pageTitle =  __('message.filter');

        $params = [
            'view_type' => request('view_type') ?? null,
        ];
        return view('customtopic.search-filter', compact('pageTitle','id','params'));
    }

}
