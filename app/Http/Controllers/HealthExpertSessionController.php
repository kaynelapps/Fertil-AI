<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthExpertSession;
use App\DataTables\HealthExpertSessionDataTable;
use App\Http\Requests\HealthExpertSessionRequest;
use Illuminate\Support\Facades\Auth;

class HealthExpertSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(HealthExpertSessionDataTable $dataTable)
    {
        if (!auth()->user()->can('healthexpertsession-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.list_form_title',['form' => __('message.session')] );
        $auth_user = auth()->user();
        $assets = ['datatable'];
        $health_expert_session = optional($auth_user->health_expert);
        $health_expert_session_data = HealthExpertSession::where('health_expert_id',$health_expert_session->id)->first();
        $button = $auth_user->can('healthexpertsession-add') ? '<a href="'.route('healthexpert-session.create').'" class="float-right btn btn-sm btn-primary jqueryvalidationLoadRemoteModel"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.session')]).'</a>' :'';
        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('healthexpertsession-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        
        $pageTitle = __('message.add_form_title',[ 'form' => __('message.session')]);
        
        return view('health_expert_session.form', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HealthExpertSessionRequest $request)
    {
        $auth_user = auth()->user();
       
        if (!$auth_user->can('healthexpertsession-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $request['health_expert_id'] = $auth_user->hasRole('admin') ? request('health_expert_id') : optional($auth_user->health_expert)->id;
        $health_expert_session = HealthExpertSession::where('health_expert_id',$request->health_expert_id)->first();
        $message = __('message.health_expert_exist');
        if($health_expert_session) {
            return response()->json(['status' => false, 'event' => 'validation','message' => $message]);
        }

        HealthExpertSession::create($request->all());
        $message = __('message.save_form', ['form' => __('message.session')]);
        
        if(request()->is('api/*')){
            return response()->json(['status' => true, 'message' => $message ]);
        }
        return response()->json(['status' => true, 'event' => 'submited','message' => $message]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('healthexpertsession-show')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('healthexpertsession-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.update_form_title',[ 'form' => __('message.session')]);
        $data = HealthExpertSession::findOrFail($id);

        return view('health_expert_session.form', compact('data', 'pageTitle', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HealthExpertSessionRequest $request, $id)
    {
        $auth_user = auth()->user();
        if (!$auth_user->can('healthexpertsession-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $health_expert_session = HealthExpertSession::find($id);
        
        $message = __('message.not_found_entry', ['name' => __('message.session')]);
        if($health_expert_session == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }
        // tags data...
        // $request['health_expert_id'] = $auth_user->hasRole('admin') ? request('health_expert_id') : optional($auth_user->health_expert)->id;

        unset($request['health_expert_id']);
        $health_expert_session->fill($request->all())->update();
        $message = __('message.update_form',['form' => __('message.session')]);
        
        if(request()->is('api/*')){
            return response()->json(['status' =>  (($health_expert_session != '') ? true : false) , 'message' => $message ]);
        }

        if(auth()->check()){
            return response()->json(['status' => true, 'event' => 'submited','message'=> $message]);
            
        }
        return response()->json(['status' => true, 'event' => 'submited', 'message'=> $message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('healthexpertsession-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $health_expert_session = HealthExpertSession::find($id);
        $status = 'error';
        $message = __('message.not_found_entry', ['name' => __('message.session')]);

        if($health_expert_session != '') {
            $health_expert_session->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.session')]);
        }
       
        if(request()->is('api/*')){
            return response()->json(['status' =>  (($health_expert_session != '') ? true : false) , 'message' => $message ]);
        }

        if(request()->ajax()) {
            return response()->json(['status' => true, 'message' => $message ]);
        }

        return redirect()->back()->with($status,$message);
    }

    public function action(Request $request)
    {  
        if (!auth()->user()->can('healthexpertsession-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $id = $request->id;
        $health_expert_session = HealthExpertSession::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.session')]);
        if ($request->type === 'restore') {
            $health_expert_session->restore();
            $message = __('message.msg_restored', ['name' => __('message.session')]);
        }

        if ($request->type === 'forcedelete') {
            $health_expert_session->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.session')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('healthexpert-session.index')->withSuccess($message);
    }
}
