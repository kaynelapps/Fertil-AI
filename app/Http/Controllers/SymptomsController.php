<?php

namespace App\Http\Controllers;
 
use App\Models\Symptoms;
use App\DataTables\SymptomsDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SymptomsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SymptomsDataTable $dataTable)
    {
        if (!Auth::user()->can('symptoms-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        
        $pageTitle = __('message.list_form_title',['form' => __('message.symptoms')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $delete_checkbox_checkout = $auth_user->can('symptoms-delete') ? '<button id="deleteSelectedBtn" checked-title = "symptoms-checked " class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('symptoms-add') ? '<a href="'.route('symptoms.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.symptoms')]).'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('symptoms-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        
        $pageTitle = __('message.add_form_title',[ 'form' => __('message.symptoms')]);
        
        return view('symptoms.form', compact('pageTitle'));
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
            return redirect()->route('symptoms.index')->withErrors($message);
        }

        if (!Auth::user()->can('symptoms-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $data = $request->all();
        $symptoms = Symptoms::create($data);

        $message = __('message.save_form', ['form' => __('message.symptoms')]);        

        return redirect()->route('symptoms.index')->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->can('symptoms-show')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.add_form_title',[ 'form' => __('message.symptoms')]);
        $data = Symptoms::findOrFail($id);

        return view('symptoms.show', compact('data','pageTitle','id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('symptoms-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.update_form_title',[ 'form' => __('message.symptoms')]);
        $data = Symptoms::findOrFail($id);
        
        return view('symptoms.form', compact('data', 'pageTitle', 'id'));
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
            return redirect()->route('symptoms.index')->withErrors($message);
        }

        if (!Auth::user()->can('symptoms-edit')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $symptoms = Symptoms::find($id);
        
        $message = __('message.not_found_entry', ['name' => __('message.symptoms')]);
        if($symptoms == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }

        $symptoms->fill($request->all())->update();

        $message = __('message.update_form',['form' => __('message.symptoms')]);

        if(request()->is('api/*')){
            return response()->json(['status' =>  (($symptoms != '') ? true : false) , 'message' => $message ]);
        }

        if(auth()->check()){
            return redirect()->route('symptoms.index')->withSuccess($message);
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
            return redirect()->route('symptoms.index')->withErrors($message);
        }

        if (!Auth::user()->can('symptoms-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $symptoms = Symptoms::find($id);            
        if($symptoms != '') {
            $symptoms->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.symptoms')]);
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
            return redirect()->route('symptoms.index')->withErrors($message);
        }

        if (!Auth::user()->can('symptoms-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $id = $request->id;
        $symptoms = Symptoms::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.symptoms')]);
        if ($request->type === 'restore') {
            $symptoms->restore();
            $message = __('message.msg_restored', ['name' => __('message.symptoms')]);
        }

        if ($request->type === 'forcedelete') {
            $symptoms->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.symptoms')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('symptoms.index')->withSuccess($message);
    }
}

