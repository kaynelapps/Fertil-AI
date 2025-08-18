<?php

namespace Modules\Frontend\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class WebSiteSectionRequest extends FormRequest
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
        $rules = [
            'sections.0.title' => 'required|max:255',
            'sections.0.subtitle' => 'required|max:255',
        ];

        return $rules;

    }

    public function messages()
    {
        $messages = [];

        $messages = [
            'sections.0.title.required' =>  __('message.request_required',['name'=>__('frontend::message.section_title')]),
            'sections.0.title.max' => __('frontend::message.max_characters', ['name' => __('frontend::message.section_title'), 'max' => 255]),

            'sections.0.subtitle.required' =>  __('message.request_required',['name'=>__('frontend::message.section_subtitle')]),
            'sections.0.subtitle.max' => __('frontend::message.max_characters', ['name' => __('frontend::message.section_subtitle'), 'max' => 255]),

        ];
        
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
