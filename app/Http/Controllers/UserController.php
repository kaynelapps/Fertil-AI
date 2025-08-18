<?php

namespace App\Http\Controllers;

use App\DataTables\SubscriptionDataTable;
use Illuminate\Http\Request;
use App\Models\User;
use App\DataTables\UserDataTable;
use App\Http\Requests\UserRequest;
use App\Models\subscriptions;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserDataTable $dataTable)
    {
        if (!Auth::user()->can('user-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $params = [
            'goal_type' => request('goal_type') ?? null,
            'app_version' => request('app_version') ?? null,
            'app_source' => request('app_source') ?? null,
            'region' => request('region') ?? null,
            'country_name' => request('country_name') ?? null,
            'city' => request('city') ?? null,
            'is_paid' => request('is_paid') ?? null
        ];
        $filterData = [
            'app_versions' => User::where('user_type','app_user')->distinct()->pluck('app_version')->filter()->unique()->sort()->values(),
            'app_sources' => User::where('user_type','app_user')->distinct()->pluck('app_source')->filter()->unique()->sort()->values(),
            'regions' => User::where('user_type','app_user')->distinct()->pluck('region')->filter()->unique()->sort()->values(),
            'countries' => User::where('user_type','app_user')->distinct()->pluck('country_name')->filter()->unique()->sort()->values(),
            'cities' => User::where('user_type','app_user')->distinct()->pluck('city')->filter()->unique()->sort()->values(),
        ];

        $pageTitle = __('message.list_form_title',['form' => __('message.app_user')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        if(request('status') == 'active') {
            $pageTitle = __('message.active_list_form_title',['form' => __('message.users')] );
        } elseif (request('status') == 'inactive') {
            $pageTitle = __('message.inactive_list_form_title',['form' => __('message.users')] );
        }

        $delete_checkbox_checkout = $auth_user->can('user-delete') ? '<button id="deleteSelectedBtn" checked-title = "users-checked" class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = '';
        $filter = '<a href="'.route('user.filter',$params).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>';

        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','button','filter','filterData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('user-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        
        $pageTitle = __('message.add_form_title',[ 'form' => __('message.app_user')]);
        $assets = ['phone'];

        return view('users.form', compact('pageTitle','assets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if (!Auth::user()->can('user-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $request['password'] = bcrypt($request->password);
        $request['display_name'] = $request['first_name']." ".$request['last_name'];

        $result = User::create($request->all());
        uploadMediaFile($result,$request->profile_image, 'profile_image');
        $result->assignRole($request->user_type);

        return redirect()->route('users.index')->withSuccess(__('message.save_form', ['form' => __('message.app_user')]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(UserDataTable $dataTable, $id)
    {
        if (!Auth::user()->can('user-show')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $user = User::where('id', $id)->first();
        $pageTitle = __('message.view_form_title',[ 'form' => __('message.users')]);
        $data = User::findOrFail($id);
        $profileImage = getSingleMedia($data, 'profile_image');
        $type = request('type') ?? 'profile';
        switch ($type) {
            case 'profile':
                return $dataTable->with($id)->render('users.show', compact('pageTitle', 'type', 'data','user','profileImage'));
                break;
            default:
                break;
        }
        return $dataTable->with($id)->render('users.show', compact('pageTitle', 'data', 'profileImage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('user-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.update_form_title',[ 'form' => __('message.app_user')]);
        $data = User::whereNotIn('user_type',['admin','user'])->findOrFail($id);
        $profileImage = getSingleMedia($data, 'profile_image');
        $assets = ['phone'];

        return view('users.form', compact('data', 'pageTitle', 'id', 'assets','profileImage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('users.index')->withErrors($message);
        }

        if (!Auth::user()->can('user-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $user = User::find($id);

        $request['display_name'] = $request['first_name']." ".$request['last_name'];
        $request['password'] = bcrypt($request->password);

        $user->removeRole($user->user_type);
        $message = __('message.not_found_entry', ['name' => __('message.app_user')]);
        if($user == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }

        $user->fill($request->all())->update();

        if (isset($request->profile_image) && $request->profile_image != null) {
            $user->clearMediaCollection('profile_image');
            $user->addMediaFromRequest('profile_image')->toMediaCollection('profile_image');
        }

        $user->assignRole($user->user_type);

        $message = __('message.update_form',[ 'form' => __('message.app_user') ]);
        
        if(auth()->check()){
           return redirect()->route('users.index')->withSuccess($message);
        }
        return redirect()->route('users.index')->withSuccess($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if (!Auth::user()->can('user-delete')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('users.index')->withErrors($message);
        }
        
        $user = User::find($id);
        $status = 'error';
        $message = __('message.not_found_entry', ['name' => __('message.app_user')]);

        if($user != '') {
            $user->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.app_user')]);
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
            return redirect()->route('users.index')->withErrors($message);
        }

          if (!Auth::user()->can('user-delete')) 
          {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
          }

        $id = $request->id;
        $users = User::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.app_user')]);
        if ($request->type === 'restore') {
            $users->restore();
            $message = __('message.msg_restored', ['name' => __('message.app_user')]);
        }

        if ($request->type === 'forcedelete') {
            $users->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.app_user')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('users.index')->withSuccess($message);
    }

    public function filter()
    {
        $pageTitle =  __('message.filter');
        $filterData = [
            'app_versions' => User::where('user_type','app_user')->distinct()->pluck('app_version')->filter()->unique()->sort()->values(),
            'app_sources' => User::where('user_type','app_user')->distinct()->pluck('app_source')->filter()->unique()->sort()->values(),
            'regions' => User::where('user_type','app_user')->distinct()->pluck('region')->filter()->unique()->sort()->values(),
            'countries' => User::where('user_type','app_user')->distinct()->pluck('country_name')->filter()->unique()->sort()->values(),
            'cities' => User::where('user_type','app_user')->distinct()->pluck('city')->filter()->unique()->sort()->values(),
        ];

        $params = [
            'goal_type' => request('goal_type') ?? null,
            'app_version' => request('app_version') ?? null,
            'app_source' => request('app_source') ?? null,
            'region' => request('region') ?? null,
            'country_name' => request('country_name') ?? null,
            'city' => request('city') ?? null,
            'is_paid' => request('is_paid') ?? null
        ];
        return view('users.filter', compact('pageTitle','params','filterData'));
    }
    // public function downloadUsersList(Request $request)
    // {        
    //     return Excel::download(new UserExport,'users'.'-'.date('Ymd_H_i_s').'.csv', \Maatwebsite\Excel\Excel::CSV);
    // }
    
}
