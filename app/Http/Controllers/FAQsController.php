<?php

namespace App\Http\Controllers;

use App\DataTables\FaqsDataTable;
use App\Http\Requests\FaqsRequest;
use App\Models\Category;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FAQsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FaqsDataTable $dataTable)
    {
        $pageTitle = __('message.list_form_title',['form' => __('message.faq')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $param = [
            'goal_type' => request('goal_type') ?? null,
        ];
        $delete_checkbox_checkout = $auth_user->can('faq-delete') ? '<button id="deleteSelectedBtn" checked-title = "faq-checked " class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('faq-add') ? '<a href="'.route('faqs.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.faq')]).'</a>' : '';
        $filter = $auth_user->can('faq-add') ? '<a href="'.route('faqs.filter',$param).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = __('message.add_form_title',[ 'form' => __('message.faq')]);

        return view('faq.form', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaqsRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('faqs.index')->withErrors($message);
        }
        // if (!Auth::user()->can('faq-add')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        $data = $request->all();
        Faq::create($data);

        $message = __('message.save_form', ['form' => __('message.faq')]);

        return redirect()->route('faqs.index')->withSuccess($message);
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
        // if (!Auth::user()->can('faq-edit')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        $pageTitle = __('message.update_form_title',[ 'form' => __('message.faq')]);
        $data = Faq::findOrFail($id);

        return view('faq.form', compact('data', 'pageTitle', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FaqsRequest $request, $id)
    {
        // if (!Auth::user()->can('faq-edit')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('faqs.index')->withErrors($message);
        }
        $faq = Faq::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.faq')]);
        if($faq == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }

        // FAQs data...
        $faq->fill($request->all())->update();

        $message = __('message.update_form',['form' => __('message.faq')]);

        if(auth()->check()){
            return redirect()->route('faqs.index')->withSuccess($message);
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
            return redirect()->route('faqs.index')->withErrors($message);
        }
        $faq = Faq::find($id);
        if($faq != '') {
            $faq->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.faq')]);
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
            return redirect()->route('faqs.index')->withErrors($message);
        }
         if (!Auth::user()->can('faq-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $id = $request->id;
        $faq = Faq::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.faq')]);
        if ($request->type === 'restore') {
            $faq->restore();
            $message = __('message.msg_restored', ['name' => __('message.faq')]);
        }

        if ($request->type === 'forcedelete') {
            $faq->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.faq')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('faqs.index')->withSuccess($message);
    }

    public function filter()
    {
        $pageTitle =  __('message.filter');

        $categoryId = request('category_id');
        $categoryName = null;

        if ($categoryId) {
            $category = Category::find($categoryId);
            if ($category) {
                $categoryName = $category->name;
            }
        }
        $params = [
            'goal_type' => request('goal_type') ?? null,
            'category_id' => request('category_id') ?? null,
            'category_name' => $categoryName

        ];
        return view('faq.filter', compact('pageTitle','params'));
    }
}
