<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Tags;
use App\DataTables\ArticleDataTable;
use App\DataTables\ArticleReferenceDataTable;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Imports\ArticleImport;
use App\Models\ArticleReference;
use App\Models\HealthExpert;
use App\Models\SubSymptoms;
use Illuminate\Support\Facades\Auth;
use App\Traits\EncryptionTrait;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Frontend\Jobs\SendPostNotification;

class ArticleController extends Controller
{
    use EncryptionTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ArticleDataTable $dataTable)
    {
        // if (!Auth::user()->can('article-list')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }
        $health_expert_id = isset($_GET['health_expert_id']) ? $_GET['health_expert_id'] : null;

        $pageTitle = __('message.list_form_title',['form' => __('message.article')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $param = [
            'goal_type' => request('goal_type') ?? null,
            'article_type' => request('article_type') ?? null,
            'sub_symptoms_id' => request('sub_symptoms_id') ?? null,
            'articles_type'=> request('articles_type') ?? null,
            'expert_id'=> request('expert_id') ?? null
        ];
        $reset_file_button = $health_expert_id != null ? '<a href="'.route('article.index').'" class="float-right mr-1 btn btn-sm btn-danger"><i class="fa fa-angle-double-left"></i> '.__('message.back').'</a>' : '';
        $delete_checkbox_checkout = $auth_user->can('article-delete') ? '<button id="deleteSelectedBtn" checked-title = "article-checked " class="float-left btn btn-sm ">'.__('message.delete_selected').'</button>' : '';
        $button = $auth_user->can('article-add') ? '<a href="'.route('article.create').'" class="float-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> '.__('message.add_form_title',['form' => __('message.article')]).'</a>' : '';
        $export = $auth_user->can('article-add') ? '<a href="'.url('export-article').'" class="float-right btn btn-sm btn-info ml-2"><i class="fa fa-download"></i> '. __('message.export').'</a>' : '';
        $import = $auth_user->can('article-add') ? '<a href="'.route('bulk.article').'" class="float-right btn btn-sm btn-primary ml-2"><i class="fas fa-file-import"></i> '. __('message.import').'</a>' : '';
        $filter = $auth_user->can('article-add') ? '<a href="'.route('article.filter',$param).'" class="loadRemoteModel float-right btn btn-sm btn-orange ml-2"><i class="fa fa-filter"></i>'.__('message.filter').'</a>' : '';
        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user','delete_checkbox_checkout','reset_file_button','export','import','filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if (!Auth::user()->can('article-add')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }
        $selected_subsymptoms = null;
        $selectedIds = null;
        $pageTitle = __('message.add_form_title',[ 'form' => __('message.article')]);
        return view('article.form', compact('pageTitle','selected_subsymptoms','selectedIds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('article.index')->withErrors($message);
        }
        $auth_user = auth()->user();
        // if (!$auth_user->can('article-add')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }
        if(request()->is('api/*')){
            $request['tags_id'] = json_decode(request('tags_id'),true);
            $request['reference'] = json_decode(request('reference'),true);
        }
        $request['sub_symptoms_id'] = json_encode(request('sub_symptoms_id'),true);
        $request['expert_id'] = $auth_user->hasRole('doctor') ? optional($auth_user->health_expert)->id : request('expert_id') ;
        $article = Article::create($request->all());

        if (isset($request->reference) && $request->reference != null) {
            foreach ($request->reference as $value) {
                if ($value != null) {
                    # code...
                    ArticleReference::create(['article_id' => $article->id,'reference' => $value]);
                }
            }
        }
        uploadMediaFile($article,$request->article_image, 'article_image');

        if ($request->has('send_mail_to_subscribers')) {
            SendPostNotification::dispatch($article);
        }
        
        $message = __('message.save_form', ['form' => __('message.article')]);
        if(request()->is('api/*')){
            return json_custom_response([ 'message'=> $message ,'data' => new ArticleResource($article) ]);
        }

        return redirect()->route('article.index')->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ArticleReferenceDataTable $articleReferenceDataTable,$id)
    {
        // if (!Auth::user()->can('article-show')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        $pageTitle = __('message.detail_form_title',['form' => __('message.article')]);
        $data = Article::findOrFail($id);
        $selected_tags = [];
        if(isset($data->tags_id)){
            $selected_tags = Tags::whereIn('id', $data->tags_id)->get()->mapWithKeys(function ($item) {
                return [ $item->id => $item->name ];
            })->implode(',');
        }
         $selectedIds = json_decode($data->sub_symptoms_id ?? '[]', true);
        $selected_subsymptoms = [];

        if (!empty($selectedIds)) {
            $selected_subsymptoms = SubSymptoms::whereIn('id', $selectedIds)->pluck('title', 'id')->toArray();
        }

        return $articleReferenceDataTable->with('article_id',$id)->render('article.show', compact('data','pageTitle','id','selected_tags','selected_subsymptoms'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if (!Auth::user()->can('article-edit')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        $pageTitle = __('message.update_form_title',[ 'form' => __('message.article')]);
        $data = Article::findOrFail($id);
        $selected_tags = [];
        if(isset($data->tags_id)){
            $selected_tags = Tags::whereIn('id', $data->tags_id)->get()->mapWithKeys(function ($item) {
                return [ $item->id => $item->name ];
            });
        }
        $selectedIds = json_decode($data->sub_symptoms_id ?? '[]', true);
        $selected_subsymptoms = [];

        if (!empty($selectedIds)) {
            $selected_subsymptoms = SubSymptoms::whereIn('id', $selectedIds)->pluck('title', 'id')->toArray();
        }

        return view('article.form', compact('data', 'pageTitle', 'id','selected_tags','selected_subsymptoms','selectedIds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, $id)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('article.index')->withErrors($message);
        }

        $auth_user = auth()->user();

        $article = Article::find($id);
        $message = __('message.not_found_entry', ['name' => __('message.article')]);
        if($article == null) {
            return response()->json(['status' => false, 'message' => $message ]);
        }

        // Article data...
        $request['expert_id'] = $auth_user->hasRole('doctor') ? optional($auth_user->health_expert)->id : request('expert_id') ;

        if (request()->is('api/*')) {
            $request['tags_id'] = json_decode(request('tags_id'),true);
            $request['reference'] = json_decode(request('reference'),true);
            $article->article_reference()->delete();
        }

        $article->fill($request->all())->update();
        $article_reference = [];

        if (isset($request->reference) && $request->reference != null) {
            foreach ($request->reference as $key => $value) {
                if ( $value != null ) {
                    if (request()->is('api/*')) {
                        $article_reference[] = ArticleReference::create(['article_id' => $article->id,'reference' => $value ?? null]);
                    }else{
                        ArticleReference::updateOrCreate(['id' => $request->article_reference_id[$key]],['article_id' => $article->id,'reference' => $value]);
                    }
                }
            }
        }

        // Save article article_image...
        if (isset($request->article_image) && $request->article_image != null) {
            $article->clearMediaCollection('article_image');
            $article->addMediaFromRequest('article_image')->toMediaCollection('article_image');
        }

        if ($request->has('send_mail_to_subscribers')) {
            SendPostNotification::dispatch($article);
        }

        $message = __('message.update_form',['form' => __('message.article')]);
        if(request()->is('api/*')){
            return response()->json([ 'message' => $message ,'data' => new ArticleResource($article)] );
        }

        if(auth()->check()){
            return redirect()->route('article.index')->withSuccess($message);
        }

        return redirect()->route('article.show')->withSuccess($message);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if (!Auth::user()->can('article-delete')) {
        //     $message = __('message.demo_permission_denied');
        //     return redirect()->back()->withErrors($message);
        // }

        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('article.index')->withErrors($message);
        }

        $article = Article::find($id);
        $status = 'error';
        $message = __('message.not_found_entry', ['name' => __('message.article')]);

        if($article != '') {
            $article->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.article')]);
        }

        if(request()->is('api/*')){
            return response()->json([
                'responseData' => $this->encryptData(['status' =>  (($article != '') ? true : false) , 'message' => $message ])
            ]);
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
            return redirect()->route('article.index')->withErrors($message);
        }

        if (!Auth::user()->can('article-delete')) {
            $message = __('message.demo_permission_denied');
            return redirect()->back()->withErrors($message);
        }

        $id = $request->id;
        $article = Article::withTrashed()->where('id', $id)->first();

        $message = __('message.not_found_entry', ['name' => __('message.article')]);
        if ($request->type === 'restore') {
            $article->restore();
            $message = __('message.msg_restored', ['name' => __('message.article')]);
        }

        if ($request->type === 'forcedelete') {
            $article->forceDelete();
            $message = __('message.msg_forcedelete', ['name' => __('message.article')]);
        }
        if (request()->is('api/*')) {
            return json_custom_response(['message' => $message, 'status' => true]);
        }

        return redirect()->route('article.index')->withSuccess($message);
    }
    public function articleReferenceDelete(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('article.index')->withErrors($message);
        }

        $id = $request->id;
        $reference = ArticleReference::findOrFail($id);
        $status = false;
        $message = __('message.not_found_entry', ['name' => __('message.reference')]);

        if($reference != '') {
            $reference->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('message.reference')]);
        }
        return response()->json(['status'=> $status, 'message'=> $message ]);
    }

    public function importFile()
    {
        $auth_user = authSession();
        if (!auth()->user()->can('article-list')) {
            $message = __('message.permission_denied_for_account');
            return redirect()->back()->withErrors($message);
        }
        $pageTitle = __('message.import_data');

        return view('article.import', compact(['pageTitle']));
    }

    public function import(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('article.index')->withErrors($message);
        }
        
        Excel::import(new ArticleImport, $request->file('file')->store('files'));

         $message = __('message.import_form_title',['form' => __('message.article')] );
        return back()->withSuccess($message);
    }
    public function templateExcel()
    {
        $file = public_path("article.xlsx");
        return response()->download($file);
    }

    public function help()
    {
        $pageTitle = __('message.import_details');

        return view('article.help', compact(['pageTitle']));
    }

    public function filter()
    {
        $pageTitle =  __('message.filter');
         $sub_symptoms_id = request('sub_symptoms_id');
         $expert_id = request('expert_id');
           $subSymptomsName = null;
           $expert_name = null;
          if ($sub_symptoms_id) {
            $subsymptoms = SubSymptoms::find($sub_symptoms_id);
            if ($subsymptoms) {
                $subSymptomsName = $subsymptoms->title;
            }
          }
          if ($expert_id) {
            $expert = HealthExpert::find($expert_id);
            if ($expert) {
                $expert_name = $expert->users->display_name;
            }
          }

        $params = [
            'goal_type' => request('goal_type') ?? null,
            'articles_type' => request('articles_type') ?? null,
            'article_type'=> request('article_type') ?? null,
            'sub_symptoms_id' => request('sub_symptoms_id') ?? null,
            'subsymptom_name' => $subSymptomsName,
            'expert_id'=> request('expert_id') ?? null,
            'expert_name'=> $expert_name
        ];
        return view('article.filter', compact('pageTitle', 'params'));
    }
}
