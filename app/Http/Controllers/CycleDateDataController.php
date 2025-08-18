<?php

namespace App\Http\Controllers;

use App\Models\CycleDateData;
use App\Models\CycleDates;
use Illuminate\Http\Request;

class CycleDateDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cycle_date_id = request()->id;
        $data = CycleDates::findOrFail($cycle_date_id);
        $pageTitle = __('message.add_form_title', ['form' => __('message.cycle_dates_data')]).' For '.__('message.cycle_day') . ' ' . $data->day;

        return view('cycle-dates.data-form', compact('pageTitle','cycle_date_id'));
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
            return redirect()->route('cycle-dates.show')->withErrors($message);
        }
        $data = $request->all();
      
        if ($request->question != null && $request->answer != null) {
            $questionsString = implode(',', $request->question);
            $answersString = implode(',', $request->answer);
           
            $data['question'] = $questionsString;
            $data['answer'] = $answersString;
        }
        $data['cycle_date_id'] = $request->cycle_date_id;
        $cycle_date_data = CycleDateData::create($data);

        uploadMediaFile($cycle_date_data,$request->cycle_date_data_text_message_image, 'cycle_date_data_text_message_image');
        uploadMediaFile($cycle_date_data,$request->cycle_date_data_que_ans_image_1, 'cycle_date_data_que_ans_image_1');
        uploadMediaFile($cycle_date_data,$request->cycle_date_data_que_ans_image_2, 'cycle_date_data_que_ans_image_2');

        $message = __('message.save_form', ['form' => __('message.cycle_dates')]);        

        return redirect()->route('cycle-dates.show',$request->cycle_date_id)->withSuccess($message);
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
        $pageTitle = __('message.update_form_title', ['form' => __('message.cycle_dates_data')]);
        $data = CycleDateData::with('cycle_date')->findOrFail($id);

        $questions = explode(',', $data->question);
        $answers = explode(',', $data->answer);

        // Combine questions and answers into an array of question-answer pairs
        $questionAnswers = [];
        foreach ($questions as $index => $question) {
            $questionAnswers[] = ['question' => $question, 'answer' => $answers[$index]];
        }

        return view('cycle-dates.data-form', compact('data','id','pageTitle', 'questionAnswers'));
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
            return redirect()->route('cycle-dates.show')->withErrors($message);
        }

        $data = $request->all();
        $cycle_date_data = CycleDateData::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.cycle_dates_data')]);
        if ($cycle_date_data == null) {
            return response()->json(['status' => false, 'message' => $message]);
        }

        if ($request->question != null && $request->answer != null) {
            $questionsString = implode(',', $request->question);
            $answersString = implode(',', $request->answer);
           
            $data['question'] = $questionsString;
            $data['answer'] = $answersString;
        }


        // Cycle date data...
        $cycle_date_data->fill($data)->update();
        uploadMediaFile($cycle_date_data,$request->cycle_date_data_text_message_image, 'cycle_date_data_text_message_image');
        uploadMediaFile($cycle_date_data,$request->cycle_date_data_que_ans_image_1, 'cycle_date_data_que_ans_image_1');
        uploadMediaFile($cycle_date_data,$request->cycle_date_data_que_ans_image_2, 'cycle_date_data_que_ans_image_2');

        $message = __('message.update_form', ['form' => __('message.cycle_dates_data')]);
        
            return redirect()->route('cycle-dates.show',$request->cycle_date_id)->withSuccess($message);
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
            return redirect()->route('cycle-dates.show')->withErrors($message);
        }

        $cycle_dates_data = CycleDateData::find($id);         
        if($cycle_dates_data != '') {
            $cycle_dates_data->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.cycle_dates')]);
        }
    
        if(request()->ajax()) {
            return response()->json(['status' => true, 'message' => $message ]);
        }

        return redirect()->back()->with($status,$message);
    }
}
