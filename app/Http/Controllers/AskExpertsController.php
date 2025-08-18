<?php

namespace App\Http\Controllers;

use App\DataTables\AskExpertsDataTable;
use App\Http\Requests\AskExpertRequest;
use App\Models\AskExperts;
use App\Traits\EncryptionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AskExpertsController extends Controller
{
    use EncryptionTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AskExpertsDataTable $dataTable)
    {
        if (!Auth::user()->can('askexpert-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.list_form_title',['form' => __('message.askexpert')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $delete_checkbox_checkout = $auth_user->can('askexpert-delete') ? '<button id="deleteSelectedBtn" checked-title = "askexpert-checked" class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = null;
        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskExpertRequest $request)
    {
        $user = auth()->user();
        // if (!Auth::user()->can('askexpert-add')) {
        //     $message = __('message.demo_permission_denied');
        //     return response()->json([
        //         'responseData' => $this->encryptData(['message' => $message])
        //     ]);
        // }
        if(env('DATA_ENCRYPTION')){
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json([
                    'responseData' => $this->encryptData([ 'status' => false,'error' => __('message.invalid_data')])
                ]);
            }
        }else{
            $decryptedData = $request->all();
        }
        $input = $decryptedData;
        if(empty($input['id'])){
            if($user->user_type == 'app_user' || $user->user_type == 'anonymous_user'){
                $input['user_id'] = $user->id;
            }else{
                return response()->json([
                    'responseData' => $this->encryptData(['status' => false,'error' => __('message.user_apply')])
                ]);
            }
        }

        if($user->user_type == 'doctor' && isset($input['id'])){
            if($user->user_type == 'doctor'){
                $input['expert_id'] = $user->id;
                $input['status'] = 1;
            }else{
                return response()->json([
                    'responseData' => $this->encryptData(['status' => false,'error' => __('message.doctor_apply')])
                ]);
            }
        }

        $askexpert = AskExperts::updateOrCreate(['id' => $input['id'] ?? null], $input);

        if ($request->hasFile('askexpert_image')) {
            foreach ($request->file('askexpert_image') as $image) {
                $askexpert->addMedia($image)->toMediaCollection('askexpert_image');
            }
        }
        if (isset($input['id'])) {
            $message = __('message.update_form', ['form' => __('message.askexpert')]);
        } else {
            $message = __('message.save_form', ['form' => __('message.askexpert')]);
        }

        if(request()->is('api/*')){
            return response()->json([
                'responseData' => $this->encryptData(['status' => true,'message' => $message])
            ]);
        }

        return redirect()->route('askexpert.index')->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = AskExperts::find($id);
        $pageTitle =  __('message.askexpert_image');

        return view('askexpert.show', compact('pageTitle','data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle =  __('message.askexpert_details');
        $data = AskExperts::find($id);
        return view('askexpert.view',compact('pageTitle','data'));
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
        $askexpert = AskExperts::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.askexpert')]);
        if($askexpert == null) {
           return response()->json([
                'responseData' => $this->encryptData(['status' => false,'message' => $message])
            ]);
        }

        $askexpert->fill($request->all())->update();

        $message = __('message.update_form',['form' => __('message.askexpert')]);

        if(request()->is('api/*')){
            return response()->json([
                'responseData' => $this->encryptData(['status' => true,'message' => $message])
            ]);
        }
        if(auth()->check()){
            return redirect()->route('askexpert.index')->withSuccess($message);
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
            return redirect()->route('ask-expert.index')->withErrors($message);
        }

        $askexpert = AskExperts::find($id);
        if($askexpert != '') {
            $askexpert->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.askexpert')]);
        }else{
             $message = __('message.not_found_entry', ['name' => __('message.askexpert')]);
        }

        if(request()->is('api/*')){
            return response()->json([
                'responseData' => $this->encryptData(['status' =>  (($askexpert != '') ? true : false) , 'message' => $message ])
            ]);
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
            return redirect()->route('ask-expert.index')->withErrors($message);
        }

        if (!Auth::user()->can('askexpert-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $id = $request->id;
        $askexpert = AskExperts::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.askexpert')]);
        if ($request->type === 'restore') {
            $askexpert->restore();
            $message = __('message.msg_restored', ['name' => __('message.askexpert')]);
        }

        if ($request->type === 'forcedelete') {
            $askexpert->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.askexpert')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('ask-expert.index')->withSuccess($message);
    }
}
