<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\PregnancyDateDataTable;
use App\Http\Requests\PregnancyDateRequest;
use App\Models\PregnancyDate;

class PregnancyDateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PregnancyDateDataTable $dataTable)
    {
        $pageTitle = __('message.list_form_title',['form' => __('message.pregnancy_date')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $delete_checkbox_checkout = $auth_user->can('pregnancydate-delete') ? '<button id="deleteSelectedBtn" checked-title = "pregnancy-date-checked " class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('pregnancydate-add') ? '<a href="'.route('pregnancy-date.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.pregnancy_date')]).'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = __('message.add_form_title', ['form' => __('message.pregnancy_date')]);
        return view('pregnancy_date.form', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PregnancyDateRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('pregnancy-date.index')->withErrors($message);
        }
        $data = PregnancyDate::create($request->all());
        uploadMediaFile($data,$request->pregnancy_date_image, 'pregnancy_date_image');
        $message = __('message.save_form', ['form' => __('message.pregnancy_date')]);

        return redirect()->route('pregnancy-date.index')->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pageTitle = __('message.add_form_title',[ 'form' => __('message.pregnancy_date')]);
        $data = PregnancyDate::findOrFail($id);

        return view('pregnancy_date.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = __('message.update_form_title', ['form' => __('message.pregnancy_date')]);
        $data = PregnancyDate::findOrFail($id);

        return view('pregnancy_date.form', compact('data', 'pageTitle', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PregnancyDateRequest $request, $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('pregnancy-date.index')->withErrors($message);
        }

        $data = PregnancyDate::find($id);

        $message = __('message.not_found_entry', ['name' => __('message.pregnancy_date')]);
        if ($data == null) {
            return response()->json(['status' => false, 'message' => $message]);
        }

        $data->fill($request->all())->update();
        if (isset($request->pregnancy_date_image) && $request->pregnancy_date_image != null) {
            $data->clearMediaCollection('pregnancy_date_image');
            $data->addMediaFromRequest('pregnancy_date_image')->toMediaCollection('pregnancy_date_image');
        }
        $message = __('message.update_form', ['form' => __('message.pregnancy_date')]);

        if (auth()->check()) {
            return redirect()->route('pregnancy-date.index')->withSuccess($message);
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
            return redirect()->route('pregnancy-date.index')->withErrors($message);
        }
        
        $data = PregnancyDate::find($id);
        $status = 'error';
        $message = __('message.not_found_entry', ['name' => __('message.pregnancy_date')]);

        if($data != '') {
            $data->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.pregnancy_date')]);
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
            return redirect()->route('pregnancy-date.index')->withErrors($message);
        }

        $id = $request->id;
        $pregnancyDate = PregnancyDate::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.pregnancy_date')]);
        if ($request->type === 'restore') {
            $pregnancyDate->restore();
            $message = __('message.msg_restored', ['name' => __('message.pregnancy_date')]);
        }

        if ($request->type === 'forcedelete') {
            $pregnancyDate->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.pregnancy_date')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('pregnancy-date.index')->withSuccess($message);
    }
}
