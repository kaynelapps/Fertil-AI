<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\DataTables\AnonymousUserDataTable;
use App\Http\Requests\AnonymousUserRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;

class AnonymousController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AnonymousUserDataTable $dataTable)
    {
        $pageTitle = __('message.list_form_title',['form' => __('message.anonymous_user')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $params = [
            'goal_type' => request('goal_type') ?? null,
            'app_version' => request('app_version') ?? null,
            'app_source' => request('app_source') ?? null,
            'region' => request('region') ?? null,
            'country_name' => request('country_name') ?? null,
            'city' => request('city') ?? null
        ];
        $filterData = [
            'app_versions' => User::where('user_type','anonymous_user')->distinct()->pluck('app_version')->filter()->unique()->sort()->values(),
            'app_sources' => User::where('user_type','anonymous_user')->distinct()->pluck('app_source')->filter()->unique()->sort()->values(),
            'regions' => User::where('user_type','anonymous_user')->distinct()->pluck('region')->filter()->unique()->sort()->values(),
            'countries' => User::where('user_type','anonymous_user')->distinct()->pluck('country_name')->filter()->unique()->sort()->values(),
            'cities' => User::where('user_type','anonymous_user')->distinct()->pluck('city')->filter()->unique()->sort()->values(),
        ];

        if(request('status') == 'active') {
            $pageTitle = __('message.active_list_form_title',['form' => __('message.anonymous_user')] );
        } elseif (request('status') == 'inactive') {
            $pageTitle = __('message.inactive_list_form_title',['form' => __('message.anonymous_user')] );
        } 

        $delete_checkbox_checkout = $auth_user->can('user-delete') ? '<button id="deleteSelectedBtn" checked-title = "anonymous-checked" class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = '';
        $filter = '<a href="'.route('anonymous.filter',$params).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>';

        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','button','filter','filterData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = __('message.add_form_title',[ 'form' => __('message.anonymous_user')]);
        $assets = ['phone'];

        return view('anonymous_user.form', compact('pageTitle','assets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnonymousUserRequest $request)
    {
        $request['display_name'] = $request['first_name']." ".$request['last_name'];
        $request['password'] = bcrypt($request->password);

        $result = User::create($request->all());
        uploadMediaFile($result,$request->profile_image, 'profile_image');
        $result->assignRole($request->user_type);

        return redirect()->route('anonymoususer.index')->withSuccess(__('message.save_form', ['form' => __('message.anonymous_user')]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AnonymousUserDataTable $dataTable, $id)
    {
        $user = User::where('id', $id)->first();
        $pageTitle = __('message.view_form_title',[ 'form' => __('message.anonymous_user')]);
        $data = User::findOrFail($id);
        $profileImage = getSingleMedia($data, 'profile_image');
        $type = request('type') ?? 'detail';

        switch ($type) {
            case 'detail':
                return $dataTable->with($id)->render('anonymous_user.show', compact('pageTitle', 'type', 'data','user','profileImage'));
                break;    
            default:
                break;
        }

        return $dataTable->with($id)->render('anonymous_user.show', compact('pageTitle', 'data', 'profileImage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = __('message.update_form_title',[ 'form' => __('message.anonymous_user')]);
        $data = User::whereNotIn('user_type',['admin','user'])->findOrFail($id);
        $profileImage = getSingleMedia($data, 'profile_image');
        $assets = ['phone'];

        return view('anonymous_user.form', compact('data', 'pageTitle', 'id', 'assets','profileImage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AnonymousUserRequest $request, $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('anonymoususer.index')->withErrors($message);
        }

        $user = User::find($id);

        $request['display_name'] = $request['first_name']." ".$request['last_name'];
        $request['password'] = bcrypt($request->password);

        $user->removeRole($user->user_type);
        $message = __('message.not_found_entry', ['name' => __('message.anonymous_user')]);
        if($user == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }

        $user->fill($request->all())->update();

        if (isset($request->profile_image) && $request->profile_image != null) {
            $user->clearMediaCollection('profile_image');
            $user->addMediaFromRequest('profile_image')->toMediaCollection('profile_image');
        }

        $user->assignRole($user->user_type);

        $message = __('message.update_form',[ 'form' => __('message.anonymous_user') ]);
        
        if(auth()->check()){
           return redirect()->route('anonymoususer.index')->withSuccess($message);
        }
        return redirect()->route('anonymoususer.index')->withSuccess($message);
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
            return redirect()->route('anonymoususer.index')->withErrors($message);
        }

        $user = User::find($id);
        $status = 'error';
        $message = __('message.not_found_entry', ['name' => __('message.anonymous_user')]);

        if($user != '') {
            $user->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.anonymous_user')]);
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
            return redirect()->route('anonymoususer.index')->withErrors($message);
        }

        $id = $request->id;
        $users = User::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.anonymous_user')]);
        if ($request->type === 'restore') {
            $users->restore();
            $message = __('message.msg_restored', ['name' => __('message.anonymous_user')]);
        }

        if ($request->type === 'forcedelete') {
            $users->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.anonymous_user')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('anonymoususer.index')->withSuccess($message);
    }
    public function downloadUsersList(Request $request)
    {        
        return Excel::download(new UserExport,'users'.'-'.date('Ymd_H_i_s').'.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function filter()
    {
        $pageTitle =  __('message.filter');
        $params = [
            'goal_type' => request('goal_type') ?? null,
            'app_version' => request('app_version') ?? null,
            'app_source' => request('app_source') ?? null,
            'region' => request('region') ?? null,
            'country_name' => request('country_name') ?? null,
            'city' => request('city') ?? null
        ];
        $filterData = [
            'app_versions' => User::where('user_type','anonymous_user')->distinct()->pluck('app_version')->filter()->unique()->sort()->values(),
            'app_sources' => User::where('user_type','anonymous_user')->distinct()->pluck('app_source')->filter()->unique()->sort()->values(),
            'regions' => User::where('user_type','anonymous_user')->distinct()->pluck('region')->filter()->unique()->sort()->values(),
            'countries' => User::where('user_type','anonymous_user')->distinct()->pluck('country_name')->filter()->unique()->sort()->values(),
            'cities' => User::where('user_type','anonymous_user')->distinct()->pluck('city')->filter()->unique()->sort()->values(),
        ];

        return view('anonymous_user.filter', compact('pageTitle','params','filterData'));
    }
}
