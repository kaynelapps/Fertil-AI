<?php

namespace App\Http\Controllers;

use App\DataTables\SubSymptomsDataTable;
use App\Models\SubSymptoms;
use App\Models\Symptoms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubSymptomsController extends Controller
{
    public function index(SubSymptomsDataTable $dataTable)
    {
        // if (!Auth::user()->can('sub-symptoms-list')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        $pageTitle = __('message.list_form_title',['form' => __('message.sub_symptoms')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $params = [
            'symptoms_id' => request('symptoms_id') ?? null,
        ];

        $delete_checkbox_checkout = $auth_user->can('symptoms-delete') ? '<button id="deleteSelectedBtn" checked-title = "sub-symptoms-checked " class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('sub-symptoms-add') ? '<a href="'.route('sub-symptoms.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.sub_symptoms')]).'</a>' : '';
        $filter = $auth_user->can('insights-add') ? '<a href="'.route('subsymptoms.filter',$params).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','filter'));
    }

    public function create()
    {
        // if (!Auth::user()->can('sub-symptoms-add')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        $pageTitle = __('message.add_form_title',[ 'form' => __('message.sub_symptoms')]);

        return view('sub-symptoms.form', compact('pageTitle'));
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
            return redirect()->route('sub-symptoms.index')->withErrors($message);
        }

        // if (!Auth::user()->can('sub-symptoms-add')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        $data = $request->all();
        $sub_symptoms = SubSymptoms::create($data);

        uploadMediaFile($sub_symptoms,$request->sub_symptom_icon, 'sub_symptom_icon');


        $message = __('message.save_form', ['form' => __('message.sub_symptoms')]);

        return redirect()->route('sub-symptoms.index')->withSuccess($message);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        // if (!Auth::user()->can('sub-symptoms-edit')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        $pageTitle = __('message.update_form_title',[ 'form' => __('message.sub_symptoms')]);
        $data = SubSymptoms::findOrFail($id);

        return view('sub-symptoms.form', compact('data', 'pageTitle', 'id'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('sub-symptoms.index')->withErrors($message);
        }
        // if (!Auth::user()->can('sub-symptoms-edit')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        $symptoms = SubSymptoms::find($id);

        $message = __('message.not_found_entry', ['name' => __('message.sub_symptoms')]);
        if($symptoms == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }

        $symptoms->fill($request->all())->update();

        if (isset($request->sub_symptom_icon) && $request->sub_symptom_icon != null) {
            $symptoms->clearMediaCollection('sub_symptom_icon');
            $symptoms->addMediaFromRequest('sub_symptom_icon')->toMediaCollection('sub_symptom_icon');
        }

        $message = __('message.update_form',['form' => __('message.sub_symptoms')]);

        if(request()->is('api/*')){
            return response()->json(['status' =>  (($symptoms != '') ? true : false) , 'message' => $message ]);
        }

        if(auth()->check()){
            return redirect()->route('sub-symptoms.index')->withSuccess($message);
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
            return redirect()->route('sub-symptoms.index')->withErrors($message);
        }
        // if (!Auth::user()->can('sub-symptoms-delete')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        $symptoms = SubSymptoms::find($id);
        if($symptoms != '') {
            $symptoms->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.sub_symptoms')]);
        }

        if(request()->is('api/*')){
            return response()->json(['status' =>  (($symptoms != '') ? true : false) , 'message' => $message ]);
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
            return redirect()->route('sub-symptoms.index')->withErrors($message);
        }

        if (!Auth::user()->can('sub-symptoms-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $id = $request->id;
        $subsymptoms = SubSymptoms::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.sub_symptoms')]);
        if ($request->type === 'restore') {
            $subsymptoms->restore();
            $message = __('message.msg_restored', ['name' => __('message.sub_symptoms')]);
        }

        if ($request->type === 'forcedelete') {
            $subsymptoms->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.sub_symptoms')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('sub-symptoms.index')->withSuccess($message);
    }

    public function filter()
    {
        $pageTitle =  __('message.filter');
        $symptoms_id = request('symptoms_id');
        $symptomsName = null;

        if ($symptoms_id) {
            $symptoms = Symptoms::find($symptoms_id);
            if ($symptoms) {
                $symptomsName = $symptoms->title;
            }
        }
        $params = [
            'symptoms_id' => request('symptoms_id') ?? null,
            'symptoms_title' => $symptomsName ?? null,
        ];
        return view('sub-symptoms.filter', compact('pageTitle','params'));
    }
}
