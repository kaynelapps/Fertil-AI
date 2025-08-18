<?php

namespace App\Http\Controllers;

use App\DataTables\SubAdminDataTable;
use App\Models\Role;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class SubAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SubAdminDataTable $dataTable)
    {
        if (!auth()->user()->can('subadmin-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $pageTitle = __('message.list_form_title', ['form' => __('message.sub_admin')]);
        $auth_user = authSession();
        $assets = ['datatable'];
        $delete_checkbox_checkout = $auth_user->can('user-delete') ? '<button id="deleteSelectedBtn" checked-title = "subadmin-checked" class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('subadmin-add') ? '<a href="' . route('sub-admin.create') . '" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> ' . __('message.add_form_title', ['form' => __('message.sub_admin')]) . '</a>' : '';
        return $dataTable->render('global.datatable', compact('assets', 'pageTitle', 'button', 'auth_user','delete_checkbox_checkout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('subadmin-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $pageTitle =  __('message.sub_admin');
        $roles = Role::where('status', 1)->whereNotIn('name', ['admin','app_user','anonymous_user','doctor','super_admin'])->get()->pluck('name', 'name');
        $assets = ['phone'];
        return view('subadmin.form', compact('pageTitle','roles','assets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {

        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('sub-admin.index')->withErrors($message);
        }

        $request['cycle_length'] = 0;
        $request['period_length'] = 0;
        $request['luteal_phase'] = 0;
        $request['display_name'] = $request->first_name . ' ' . $request->last_name;
        $request['password'] = bcrypt($request->password);

        $result = User::create($request->all());
        uploadMediaFile($result,$request->profile_image, 'profile_image');
        $result->assignRole($request->user_type);

        $message = __('message.save_form', ['form' => __('message.sub_admin')]);
        if ($request->is('api/*')) {
            return json_message_response($message);
        }

        return redirect()->route('sub-admin.index')->withSuccess($message);
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
        if (!auth()->user()->can('subadmin-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $pageTitle = __('message.update_form_title',[ 'form' => __('message.sub_admin')]);
        $data = User::whereNotIn('user_type',['admin','app_user','anonymous_user','doctor'])->findOrFail($id);
        $profileImage = getSingleMedia($data, 'profile_image');
        $assets = ['phone'];
        $roles = Role::where('status', 1)->whereNotIn('name', ['admin','app_user','anonymous_user','doctor'])->get()->pluck('name', 'name');

        return view('subadmin.form', compact('data', 'pageTitle', 'id', 'assets','roles','profileImage'));
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
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('sub-admin.index')->withErrors($message);
        }

        if (!auth()->user()->can('subadmin-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $user = User::find($id);

        $user->removeRole($user->user_type);
        $message = __('message.not_found_entry', ['name' => __('message.sub_admin')]);
        if($user == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }

        $user->fill($request->all())->update();

        if (isset($request->profile_image) && $request->profile_image != null) {
            $user->clearMediaCollection('profile_image');
            $user->addMediaFromRequest('profile_image')->toMediaCollection('profile_image');
        }

        $user->assignRole($request['user_type']);

        $message = __('message.update_form',[ 'form' => __('message.sub_admin') ]);

        if ($request->is('api/*')) {
            return json_message_response($message);
        }

        if(auth()->check()){
           return redirect()->route('sub-admin.index')->withSuccess($message);
        }
        return redirect()->route('sub-admin.index')->withSuccess($message);
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
            return redirect()->route('sub-admin.index')->withErrors($message);
        }

        if (!auth()->user()->can('subadmin-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $user = User::find($id);
        $status = 'error';
        $message = __('message.not_found_entry', ['name' => __('message.sub_admin')]);

        if($user != '') {
            $user->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.sub_admin')]);
        }

        if(request()->is('api/*')){
            return response()->json(['status' => true, 'message' => $message ]);
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
            return redirect()->route('sub-admin.index')->withErrors($message);
        }

        if (!auth()->user()->can('subadmin-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $id = $request->id;
        $users = User::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.sub_admin')]);
        if ($request->type === 'restore') {
            $users->restore();
            $message = __('message.msg_restored', ['name' => __('message.sub_admin')]);
        }

        if ($request->type === 'forcedelete') {
            $users->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.sub_admin')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('sub-admin.index')->withSuccess($message);
    }
}
