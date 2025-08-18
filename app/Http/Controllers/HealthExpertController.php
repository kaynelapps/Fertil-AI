<?php

namespace App\Http\Controllers;

use App\DataTables\ArticleDataTable;
use App\DataTables\EducationalSessionDataTable;
use App\DataTables\HealthExpertsDataTable;
use App\Http\Requests\HealthExpertRequest;
use App\Models\HealthExpert;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HealthExpertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(HealthExpertsDataTable $dataTable)
    {
        if (!Auth::user()->can('health-experts-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.list_form_title',['form' => __('message.health_experts')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $delete_checkbox_checkout = $auth_user->can('health-experts-delete') ? '<button id="deleteSelectedBtn" checked-title = "health-experts-checked " class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('health-experts-add') ? '<a href="'.route('health-experts.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.health_experts')]).'</a>' : '';
        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('health-experts-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.add_form_title',[ 'form' => __('message.health_experts')]);

        return view('health-experts.form', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HealthExpertRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('health-experts.index')->withErrors($message);
        }

        if (!Auth::user()->can('health-experts-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $user_type = 'doctor';
        $doctor_data = User::create([
            'first_name' => $request->name,
            'display_name' => $request->name,
            'email'      => $request->email,
            'password'   => bcrypt($request->email),
            'status'     => $request->status,
            'user_type'  => $user_type,
            'cycle_length' => 0,
            'period_length' => 0,
            'luteal_phase' => 0,
        ]);

        $doctor_data->assignRole($user_type);
        $request['user_id'] = $doctor_data->id;

        $data = $request->all();
        unset($data['name']);
        unset($data['email']);
        unset($data['status']);

        $expert_data = HealthExpert::create($data);

        uploadMediaFile($expert_data,$request->health_experts_image, 'health_experts_image');

        $message = __('message.save_form', ['form' => __('message.health_experts')]);

        return redirect()->route('health-experts.index')->withSuccess($message);
    }

    public function show(ArticleDataTable $dataTable, $id)
    {
        if (!Auth::user()->can('category-show')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        $data = HealthExpert::findOrFail($id);
        $pageTitle = $data->users->display_name ?? '';
        $profileImage = getSingleMedia($data, 'health_experts_image');
        $type = request('type') ?? 'profile';

        switch ($type) {
            case 'profile':
                $healthExpert_id = $id;
                return view('health-experts.show', compact('type', 'data', 'id', 'pageTitle', 'profileImage'));
                break;

            case 'change_password':
                return view('health-experts.show', compact('type', 'data','id','pageTitle'));
                break;

            case 'blogs':
                return $dataTable->with(['expert_id' => $id])->render('health-experts.show', compact('type', 'data','id','pageTitle'));
                break;


            default:
                # code...
                break;
            }

        return $dataTable->render('health-experts.show', compact('type', 'data','id'));
    }

    public function edit($id)
    {
        if (!Auth::user()->can('health-experts-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.update_form_title',[ 'form' => __('message.health_experts')]);
        $data = HealthExpert::findOrFail($id);

        return view('health-experts.form', compact('data', 'pageTitle', 'id'));
    }

    public function update(HealthExpertRequest $request, $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('health-experts.index')->withErrors($message);
        }

        if (!Auth::user()->can('health-experts-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $expert_data = HealthExpert::find($id);
        $user_type = 'doctor';
        $user = User::find($expert_data->user_id);
        $user->removeRole($user->user_type);

        $user->fill([
            'first_name' => $request->name,
            'display_name' => $request->name,
            'status'     => $request->status,
            'user_type'  => $user_type,
        ])->update();

        $user->assignRole($user_type);

        $message = __('message.not_found_entry', ['name' => __('message.health_experts')]);
        if($expert_data == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }

        // Health Expert data...
        unset($request['name']);
        unset($request['email']);
        unset($request['status']);

        $expert_data->fill($request->all())->update();

        if (isset($request->health_experts_image) && $request->health_experts_image != null) {
            $expert_data->clearMediaCollection('health_experts_image');
            $expert_data->addMediaFromRequest('health_experts_image')->toMediaCollection('health_experts_image');
        }

        $message = __('message.update_form',['form' => __('message.health_experts')]);

        if(request()->is('api/*')){
            return response()->json(['status' =>  (($expert_data != '') ? true : false) , 'message' => $message ]);
        }

        if(auth()->check()){
            return redirect()->route('health-experts.index')->withSuccess($message);
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
            return redirect()->route('health-experts.index')->withErrors($message);
        }

        $expert_data = HealthExpert::find($id);
        if($expert_data != '') {
            $expert_data->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.health_experts')]);
        }

        if(request()->ajax()) {
            return response()->json(['status' => true, 'message' => $message ]);
        }

        return redirect()->back()->with($status,$message);
    }

    public function accessPasswordForm(Request $request)
    {
        $health_id = $request->id;
        $pageTitle = __('message.add_form_title',['form' => __('message.new_password')] );
        return view('health-experts.health_experts_password', compact('pageTitle','health_id'));
    }

    public function accessPasswordStore(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('health-experts.index')->withErrors($message);
        }


        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            if(request()->ajax()) {
                return response()->json([ 'status' => false, 'event' => 'validation', 'message' => $validator->errors()->first()]);
            }
            return redirect()->back()->with('error',$validator->errors()->first());
        }

        $expert_data = HealthExpert::find($request->id);
        $message = __('message.action_is_unauthorized', [ 'name' => __('message.password') ]);
        $status = 'error';

        $response = ['status' => false, 'event' => 'validation','message' => $message];
        if($expert_data != '') {
            $user = User::find($expert_data->user_id);
            $expert_data->is_access = 1;
            $expert_data->save();
            $user->password = bcrypt($request->password);
            $user->save();
            $status = 'success';
            $message = __('message.save_form', ['form' => __('message.password')]);
            $response = ['status' => true, 'event' => 'submited','message' => $message];
        }
        if(request()->ajax()) {
            return response()->json($response);
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
            return redirect()->route('health-experts.index')->withErrors($message);
        }

        $id = $request->id;
        $healthexpert = HealthExpert::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.health_experts')]);
        if ($request->type === 'restore') {
            $healthexpert->restore();
            $message = __('message.msg_restored', ['name' => __('message.health_experts')]);
        }

        if ($request->type === 'forcedelete') {
            $user = User::withTrashed()->where('id', $healthexpert->user_id)->first();
            $healthexpert->forceDelete();
            $user->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.health_experts')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('health-experts.index')->withSuccess($message);
    }
}
