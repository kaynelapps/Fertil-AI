<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class AnonymousUserRequest extends FormRequest
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
        $rules = [];
        if(request()->is('api*')) {
            $user_id = auth()->user()->id ?? request()->id;
            $rules = [
                'email' => 'required|max:191|email|unique:users,email,'.$user_id,
                'goal_type' => 'required|numeric|in:0,1,2,'.$user_id,
                'user_type' => 'required|in:anonymous_user,app_user,'.$user_id,
            ];
        } else {
            
            $method = strtolower($this->method());
            $user_id = $this->route()->anonymoususer;

            switch ($method) {
                case 'post':
                    $rules = [
                        'email' => 'required|max:191|email|unique:users',
                    ];
                break;
                case 'patch':
                    $rules = [
                        'email' => 'required|max:191|email|unique:users,email,'.$user_id,
                    ];
                break;
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            
        ];
    }

     /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator){
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
