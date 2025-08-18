<?php

namespace App\Http\Controllers;

use App\DataTables\CalculatorToolDataTable;
use App\Models\CalculatorTool;
use Illuminate\Http\Request;

class CalculatorToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CalculatorToolDataTable $dataTable)
    {
        $pageTitle = __('message.list_form_title',['form' => __('message.calculator_tool')] );
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
        $pageTitle = __('message.update_form_title', ['form' => __('message.calculator_tool')]);
        $data = CalculatorTool::findOrFail($id);

        return view('calculator_tool.form', compact('data', 'pageTitle', 'id'));
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
            return redirect()->route('calculator-tool.index')->withErrors($message);
        }

        $data = CalculatorTool::find($id);

        $message = __('message.not_found_entry', ['name' => __('message.calculator_tool')]);
        if ($data == null) {
            return response()->json(['status' => false, 'message' => $message]);
        }

        $data->fill($request->all())->update();
        if (isset($request->calculator_thumbnail_image) && $request->calculator_thumbnail_image != null) {
            $data->clearMediaCollection('calculator_thumbnail_image');
            $data->addMediaFromRequest('calculator_thumbnail_image')->toMediaCollection('calculator_thumbnail_image');
        }
        $message = __('message.update_form', ['form' => __('message.calculator_tool')]);

        return redirect()->route('calculator-tool.index')->withSuccess($message);
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
