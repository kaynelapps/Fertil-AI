<?php

namespace Modules\Frontend\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class FrontendSettingRequest extends FormRequest
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

        if ($this->ajax()) {
            $rules = [
                'title' => 'required',
                'subtitle' => in_array($this->type,['user-review']) ? 'required' : '',
                'description' => in_array($this->type,['user-review']) ? 'required' : '',
            ];
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        if ($this->type === 'user-review') {
            $messages = [
                'title.required' =>  __('message.request_required',['name'=>__('frontend::message.username')]),
                'subtitle.required' =>  __('message.request_required',['name'=>__('frontend::message.country')]),
            ];
        }

        return $messages;
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
            throw new HttpResponseException(response()->json(['status' => false, 'event'=>'validation', 'message' => $validator->errors()->first()]));
        } else {
            throw new HttpResponseException(redirect()->back()->withInput()->with('errors', $validator->errors()));
        }
    }
}
