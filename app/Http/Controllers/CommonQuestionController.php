<?php

namespace App\Http\Controllers;

use App\DataTables\CommonQuestionDataTable;
use App\Http\Requests\CommonQuestionRequest;
use App\Models\Category;
use App\Models\CommonQuestions;
use Illuminate\Support\Facades\Auth;

class CommonQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CommonQuestionDataTable $dataTable)
    {
        // if (!Auth::user()->can('common-question-list')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        $params = [ 
            'category_id' => request('category_id') ?? null,
        ];
        $pageTitle = __('message.list_form_title',['form' => __('message.common_que_ans')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $delete_checkbox_checkout = $auth_user->can('common-question-delete') ? '<button id="deleteSelectedBtn" checked-title = "common-que-checked " class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('common-question-add') ? '<a href="'.route('common-question.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.common_que_ans')]).'</a>' : '';
        $filter = $auth_user->can('common-question-add') ? '<a href="'.route('common-question.filter',$params).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if (!Auth::user()->can('common-question-add')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        $pageTitle = __('message.add_form_title',[ 'form' => __('message.common_que_ans')]);
        
        return view('common-question.form', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommonQuestionRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('common-question.index')->withErrors($message);
        }

        if (!Auth::user()->can('common-question-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $data = $request->all();
        $common_que = CommonQuestions::create($data);

        $message = __('message.save_form', ['form' => __('message.common_que_ans')]);        

        return redirect()->route('common-question.index')->withSuccess($message);
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
        // if (!Auth::user()->can('common-question-edit')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        $pageTitle = __('message.update_form_title',[ 'form' => __('message.common_que_ans')]);
        $data = CommonQuestions::findOrFail($id);
        
        return view('common-question.form', compact('data', 'pageTitle', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommonQuestionRequest $request, $id)
    {
        // if (!Auth::user()->can('common-question-edit')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('common-question.index')->withErrors($message);
        }

        $common_que = CommonQuestions::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.common_que_ans')]);
        if($common_que == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }

        // common_que data...
        $common_que->fill($request->all())->update();

        $message = __('message.update_form',['form' => __('message.common_que_ans')]);

        if(request()->is('api/*')){
            return response()->json(['status' =>  (($common_que != '') ? true : false) , 'message' => $message ]);
        }

        if(auth()->check()){
            return redirect()->route('common-question.index')->withSuccess($message);
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
        // if (!Auth::user()->can('common-question-delete')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('common-question.index')->withErrors($message);
        }

        $common_que = CommonQuestions::find($id);            
        if($common_que != '') {
            $common_que->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.common_que_ans')]);
        }
        
        if(request()->is('api/*')){
            return response()->json(['status' =>  (($common_que != '') ? true : false) , 'message' => $message ]);
        }

        if(request()->ajax()) {
            return response()->json(['status' => true, 'message' => $message ]);
        }

        return redirect()->back()->with($status,$message);
    }

    public function action(CommonQuestionRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('common-question.index')->withErrors($message);
        }

         if (!Auth::user()->can('common-question-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
        
        $id = $request->id;
        $common_que = CommonQuestions::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.common_que_ans')]);
        if ($request->type === 'restore') {
            $common_que->restore();
            $message = __('message.msg_restored', ['name' => __('message.common_que_ans')]);
        }

        if ($request->type === 'forcedelete') {
            $common_que->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.common_que_ans')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('common-question.index')->withSuccess($message);
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
            'category_id' => request('category_id') ?? null,
            'category_name' => $categoryName

        ];
        return view('common-question.filter', compact('pageTitle','params'));
    }
}
