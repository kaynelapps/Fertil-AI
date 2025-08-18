<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $auth_user = auth()->user();

        $method = strtolower($this->method());
        $rules = [];

        if(request()->is('api/*')){
            $rules = [
                'name' => 'required',
                'tags_id' => 'required',
                'goal_type' => 'required|in:0,1,2',
                'expert_id' => $auth_user->hasRole('doctor') ? '' : 'required',
                'status' => 'required|in:0,1',
            ];
        }else{
            switch ($method) {
                case 'post':
                    $rules = [
                        'name' => 'required',
                        'tags_id' => 'required',
                        'goal_type' => 'required',
                        'expert_id' => $auth_user->hasRole('doctor') ? '' : 'required',
                        'status' => 'required',
                    ];
                    break;
                case 'patch':
                    $rules = [
                        'name' => 'required',
                        'tags_id' => 'required',
                        'goal_type' => 'required',
                        'expert_id' => $auth_user->hasRole('doctor') ? '' : 'required',
                        'status' => 'required',
                    ];
                    break;
            }
        }
      

        return $rules;
    }

    public function messages()
    {
        return [ 
            'tags_id.required' => __('message.request_required',['name'=>__('message.tags')]),
            'expert_id.required' => __('message.request_required',['name'=>__('message.health_experts')]),
        ];
    }
    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $data = [
            'status' => true,
            'message' => $validator->errors()->first(),
            'all_message' =>  $validator->errors()
        ];

        if ( request()->is('api*')){
           throw new HttpResponseException( response()->json($data,422) );
        }

        if ($this->ajax()) {
            throw new HttpResponseException(response()->json($data,422));
        } else {
            throw new HttpResponseException(redirect()->back()->withInput()->with('errors', $validator->errors()));
        }
    }
}
