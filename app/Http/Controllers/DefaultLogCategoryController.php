<?php

namespace App\Http\Controllers;

use App\DataTables\DefaultLogCategoryDataTable;
use App\Models\DefaultLogCategory;
use Illuminate\Http\Request;

class DefaultLogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DefaultLogCategoryDataTable $dataTable)
    {
        $pageTitle = __('message.list_form_title',['form' => __('message.default_log_category')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $button = '';

        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user'));
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
    public function store(Request $request)
    {
        //
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
        $pageTitle = __('message.update_form_title', ['form' => __('message.default_log_category')]);
        $data = DefaultLogCategory::findOrFail($id);

        return view('default-log-category.form', compact('data', 'pageTitle', 'id'));
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
            return redirect()->route('default-log-category.index')->withErrors($message);
        }

        $data = DefaultLogCategory::find($id);

        $message = __('message.not_found_entry', ['name' => __('message.default_log_category')]);
        if ($data == null) {
            return response()->json(['status' => false, 'message' => $message]);
        }

        $data->fill($request->all())->update();
        if (isset($request->log_category_image) && $request->log_category_image != null) {
            $data->clearMediaCollection('log_category_image');
            $data->addMediaFromRequest('log_category_image')->toMediaCollection('log_category_image');
        }
        $message = __('message.update_form', ['form' => __('message.default_log_category')]);

        return redirect()->route('default-log-category.index')->withSuccess($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
