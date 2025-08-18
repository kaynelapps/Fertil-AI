<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class HealthExpertRequest extends FormRequest
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
        $method = strtolower($this->method());
        $user_id = $this->route()->users;

        $rules = [];
        if(request()->is('api/*')){
            $rules = [
                'name' => 'required',
                'tag_line' => 'required',
                'short_description' => 'required',
            ];
        }else{
            switch ($method) {
                case 'post':
                    $rules = [
                        'email' => 'required|max:191|email|unique:users',
                        'name' => 'required',
                        'tag_line' => 'required',
                        'short_description' => 'required',
                        // 'health_experts_image' => 'required',
                    ];
                    break;
                case 'patch':
                    $rules = [
                        // 'email' => 'required|max:191|email|unique:users,email,'.$user_id,
                        'name' => 'required',
                        'tag_line' => 'required',
                        'short_description' => 'required',
                    ];
                    break;
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [ ];
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
