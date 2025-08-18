<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Http\Resources\ArticleResource;
use App\Models\BookmarkActicle;
use App\Traits\EncryptionTrait;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    use EncryptionTrait;
    
    public function getList(Request $request)
    {
        if(env('DATA_ENCRYPTION')){
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        }else{
            $decryptedData = $request->all();
        }
        $input = $decryptedData;

        $article = Article::GetArticle()->where('status',1);

        if(!empty($input['name'])){
            $article->when($input['name'], function ($q) use ($input) {
                return $q->where('name', 'LIKE', '%' . $input['name'] . '%');
            });
        }

        if(!empty($input['tags_id'])){
            $article->when($input['tags_id'], function ($query) use ($input) {
                return $query->whereJsonContains('tags_id', $input['tags_id']);
            });
        }
                
        $per_page = config('constant.PER_PAGE_LIMIT');
        if(!empty($input['per_page'])){
            if(is_numeric($input['per_page']))
            {
                $per_page = $input['per_page'];
            }
            if($input['per_page'] == -1 ){
                $per_page = $article->count();
            }
        }

        $article = $article->orderBy('id', 'desc')->paginate($per_page);

        $items = ArticleResource::collection($article);
        $status ='true';

        $response = [
            'status'        => $status,
            'pagination'    => json_pagination_response($items),
            'data'          => $items
        ];
        
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }
    

   public function articleList(Request $request)
    {
        $auth_user = auth()->user();

        if (env('DATA_ENCRYPTION')) {
            $decryptedData = json_decode($this->decryptData($request->input('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        } else {
            $decryptedData = $request->all();
        }

        $input = $decryptedData;
        if ($auth_user->goal_type == 0) {
            $article = Article::where('status', 1);
            if (isset($input['cycle_day'])) {
                if ($input['cycle_day'] >= 1 && $input['cycle_day'] <= 5) {
                    $article->where('article_type', '1')->where('goal_type', 0);
                } elseif ($input['cycle_day'] >= 6 && $input['cycle_day'] <= 13) {
                    $article->where('article_type', '2')->where('goal_type', 0);
                } elseif ($input['cycle_day'] >= 14 && $input['cycle_day'] <= 15) {
                    $article->where('article_type', '3')->where('goal_type', 0);
                } elseif ($input['cycle_day'] >= 16 && $input['cycle_day'] <= 28) {
                    $article->where('article_type', '4')->where('goal_type', 0);
                } elseif ($input['cycle_day'] > 28) {
                    $article->whereIn('article_type', ['5', '6'])->where('goal_type', 0);
                }else {
                     $article->whereRaw('0 = 1'); 
                }
            }
        } else {
            $article = Article::where('status', 1);
            $article->where('goal_type', 1)
                    ->where(function ($query) use ($input) {
                        if (isset($input['week'])) {
                            $query->orWhere('weeks', $input['week']);
                        }
                    });
        }
        $encryptedData = $input['encData'];
        $key = substr(hash('sha256', env('SECRET_KEY')), 0, 32);
        $iv = substr(hash('sha256', env('VIKEY')), 0, 16);

        $decryptedData = openssl_decrypt(
            base64_decode($encryptedData),
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        $symptomIds = json_decode($decryptedData, true);
        if($symptomIds != []){
            $article = Article::where('goal_type', $auth_user->goal_type)
               ->where('article_type', 0)
               ->where(function($query) use ($symptomIds) {
                   foreach ($symptomIds as $id) {
                       $query->orWhereRaw("JSON_CONTAINS(sub_symptoms_id, '\"{$id}\"')");
                   }
               });
        }else{
            $article = collect();
        }

        if (!empty($input['name'])) {
            $article->where('name', 'LIKE', '%' . $input['name'] . '%');
        }
        $per_page = config('constant.PER_PAGE_LIMIT');
        if (!empty($input['per_page'])) {
            if (is_numeric($input['per_page'])) {
                $per_page = $input['per_page'];
            }
            if ($input['per_page'] == -1) {
                $per_page = $article->count();
            }
        }

        $article = $article->orderBy('id', 'desc')->paginate($per_page);

        $items = ArticleResource::collection($article);

        $response = [
            'status'     => 'true',
            'pagination' => json_pagination_response($items),
            'data'       => $items
        ];

        return response()->json(['responseData' => $this->encryptData($response) ]);
    }

    public function tagArticleList(Request $request)
    {
        if(env('DATA_ENCRYPTION')){
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        }else{
            $decryptedData = $request->all();
        }
        $input = $decryptedData;

        $article = Article::where('status',1);

        if(!empty($input['tag_id'])){
            $article->when($input['tag_id'], function ($query) use ($input) {
                return $query->whereJsonContains('tags_id', $input['tag_id']);
            });
        }
                
        $per_page = config('constant.PER_PAGE_LIMIT');
        if(!empty($input['per_page'])){
            if(is_numeric($input['per_page']))
            {
                $per_page = $input['per_page'];
            }
            if($input['per_page'] == -1 ){
                $per_page = $article->count();
            }
        }

        $article = $article->orderBy('id', 'desc')->paginate($per_page);

        $items = ArticleResource::collection($article);
        $status ='true';

        $response = [
            'status'        => $status,
            'pagination'    => json_pagination_response($items),
            'data'          => $items
        ];
        
        return response()->json(['responseData' => $this->encryptData($response) ]);
    }

    public function getDetail(Request $request)
    {
        if(env('DATA_ENCRYPTION')){
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        }else{
            $decryptedData = $request->all();
        }
        $input = $decryptedData;

        $id = $input['id'] ?? null;

        $article = Article::GetArticle()->where('id',$id)->where('status',1)->first();
        if(empty($article))
        {
            $message = __('message.not_found_entry',['name' =>__('message.article')]);
            $response = ['message' => $message ];
            
            return response()->json(['responseData' => $this->encryptData($response) ]);
        }

        $article_detail = new ArticleResource($article);
        $response = ['data' => $article_detail ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
    }
    public function bookmarkActicle(Request $request)
    {
        if(env('DATA_ENCRYPTION')){
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        }else{
            $decryptedData = $request->all();
        }
        $input = $decryptedData;

        if (!$input) {
            return response()->json(['responseData' => $this->encryptData(['error' => 'Invalid JSON'])]);
        }

        $article = Article::find($input['article_id']);
        if (!$article) {
            return response()->json(['responseData' => $this->encryptData(['message' => __('message.not_found_entry', ['name' => __('message.article')])])]);
        }

        $validatedData = Validator::make($input, ['is_bookmark' => 'required|numeric|in:0,1']);
        if ($validatedData->fails()) {
            return response()->json(['responseData' => $this->encryptData(['errors' => $validatedData->errors()])]);
        }

        $auth_user = auth()->user();
        $request['user_id'] = $auth_user->id;
        $request['article_id'] = $input['article_id'];
        $request['is_bookmark'] = $input['is_bookmark'];

        $bookmarksActicles = BookmarkActicle::updateOrCreate(['article_id' => $input['article_id'],'user_id' => $auth_user->id],$input);
        if ($request->is_bookmark == "1") {
            $message = __('message.bookmarks_articles');
        } else {
            $message = __('message.remove_bookmarks_articles');
        }
        $response = [
            'message' => $message,
            'data' => $bookmarksActicles,
        ];
        return response()->json(['responseData' => $this->encryptData($response)]);
        return response()->json($response);
        // return json_custom_response($response);
    }
    public function getBookmarkActicleList(Request $request)
    {
        if(env('DATA_ENCRYPTION')){
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        }else{
            $decryptedData = $request->all();
        }
        $input = $decryptedData;

        $auth_user = auth()->user();
        $bookmark_articles = BookmarkActicle::where('user_id', $auth_user->id)->where('is_bookmark', 1);

        if(!empty($input['id'])){
            $bookmark_articles->when($input['id'], function ($q) use ($input) {
                return $q->where('id', 'LIKE', '%' . $input['id'] . '%');
            });
        }

        $bookmark_articles_id = $bookmark_articles->get()->pluck('article_id');
        $articles = Article::whereIn('id',$bookmark_articles_id)->where('status',1);

        $per_page = config('constant.PER_PAGE_LIMIT');
        if(!empty($input['per_page'])){
            if(is_numeric($input['per_page']))
            {
                $per_page = $input['per_page'];
            }
            if($input['per_page'] == -1 ){
                $per_page = $articles->count();
            }
        }

        $articles = $articles->orderBy('id', 'desc')->paginate($per_page);

        $items = ArticleResource::collection($articles);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items,
        ];
        
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }
}
