<?php

namespace App\Http\Controllers;

use App\DataTables\DailyInsightsDataTable;
use App\Models\DailyInsights;
use App\Models\SubSymptoms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DailyInsightsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DailyInsightsDataTable $dataTable)
    {
       if (!Auth::user()->can('dailyinsight-list')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }
         $params = [
            'goal_type' => request('goal_type') ?? null,
            'sub_symptoms_id' => request('sub_symptoms_id') ?? null,
            'phase' => request('phase') ?? null,
        ];

        $pageTitle = __('message.daily_insight_tips' );
        $auth_user = authSession();
        $assets = ['datatable'];
        $delete_checkbox_checkout = $auth_user->can('dailyinsight-delete') ? '<button id="deleteSelectedBtn" checked-title = "dailyinsight-checked " class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('dailyinsight-add') ? '<a href="'.route('dailyInsight.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.daily_insight')]).'</a>' : '';
        $filter = $auth_user->can('dailyinsight-add') ? '<a href="'.route('dailyinsights.filter',$params).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>' : '';

        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       if (!Auth::user()->can('dailyinsight-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $pageTitle = __('message.add_form_title', ['form' => __('message.daily_insight')]);

        return view('dailyinsight.form', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('dailyInsight.index')->withErrors($message);
        }
         if (!Auth::user()->can('dailyinsight-add')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $data = $request->all();
        if ($request->has('sub_symptoms_id')) {
            $data['sub_symptoms_id'] = json_encode($request->input('sub_symptoms_id'));
        }

        $cycleDate =  DailyInsights::create($data);

        $message = __('message.save_form', ['form' => __('message.daily_insight')]);        

        return redirect()->route('dailyInsight.index')->withSuccess($message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = __('message.update_form_title',[ 'form' => __('message.daily_insight')]);
        $data = DailyInsights::findOrFail($id);
        $ids = json_decode($data->sub_symptoms_id, true);

        $selectedSubsymptoms = [];
        if(isset($ids)){            
            $selectedSubsymptoms = SubSymptoms::whereIn('id', $ids)->get()->mapWithKeys(function ($item) {
                return [ $item->id => $item->title ];
            });
        }
        return view('dailyinsight.form', compact('data', 'pageTitle', 'id', 'selectedSubsymptoms','ids'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('dailyInsight.index')->withErrors($message);
        }
        $faq = DailyInsights::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.daily_insight')]);
        if($faq == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }

        // FAQs data...
        $faq->fill($request->all())->update();

        $message = __('message.update_form',['form' => __('message.daily_insight')]);

        if(auth()->check()){
            return redirect()->route('dailyInsight.index')->withSuccess($message);
        }
        return redirect()->back()->withSuccess($message);
    }

    public function destroy(string $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('dailyInsight.index')->withErrors($message);
        }

       $faq = DailyInsights::find($id);
        if($faq != '') {
            $faq->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.daily_insight')]);
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
            return redirect()->route('dailyInsight.index')->withErrors($message);
        }

        if (!Auth::user()->can('dailyinsight-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $id = $request->id;
        $daiyinsights = DailyInsights::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.daily_insight')]);
        if ($request->type === 'restore') {
            $daiyinsights->restore();
            $message = __('message.msg_restored', ['name' => __('message.daily_insight')]);
        }

        if ($request->type === 'forcedelete') {
            $daiyinsights->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.daily_insight')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('dailyInsight.index')->withSuccess($message);
    }

     public function filter()
    {
        $pageTitle =  __('message.filter');

        $subsymptomsId = request('sub_symptoms_id');
        $subsymptomsTitle = null;

        if ($subsymptomsId) {
            $subsymptoms = SubSymptoms::find($subsymptomsId);
            if ($subsymptoms) {
                $subsymptomsTitle = $subsymptoms->title;
            }
        }
        $params = [
            'goal_type' => request('goal_type') ?? null,
            'sub_symptoms_id' => request('sub_symptoms_id') ?? null,
            'phase' => request('phase') ?? null,
            'subsymptom_name' => $subsymptomsTitle

        ];
        return view('dailyinsight.filter', compact('pageTitle','params'));
    }
}
