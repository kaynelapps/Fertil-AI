<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class HealthExpertSessionRequest extends FormRequest
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

        $rules = [];
        if(request()->is('api/*')){
           
        }else{
            if (auth()->user()->hasRole('admin')) {
                $rules['health_expert_id'] = 'required';
            }
            switch ($method) {
                case 'post':
                    $postRules = [
                        'week_days' => 'required',
                        'morning_start_time' => 'required|date_format:H:i',
                        'morning_end_time' => 'required|date_format:H:i|after:morning_start_time',
                        'evening_start_time' => 'required|date_format:H:i',
                        'evening_end_time' => 'required|date_format:H:i|after:evening_start_time',
                    ];
                    $rules = array_merge($rules, $postRules);
                    break;
                case 'patch':
                    $rules = [
                        'week_days' => 'required',
                        'morning_start_time' => 'required|date_format:H:i',
                        'morning_end_time' => 'required|date_format:H:i|after:morning_start_time',
                        'evening_start_time' => 'required|date_format:H:i',
                        'evening_end_time' => 'required|date_format:H:i|after:evening_start_time',
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
        $fields = ['health_expert_id', 'week_days', 'morning_start_time', 'morning_end_time', 'evening_start_time', 'evening_end_time'];
        $required_field = array_combine($fields, $fields);

        if ( request()->is('api*')){
           throw new HttpResponseException( response()->json($data,422));
        }

        if ($this->ajax()) {
            throw new HttpResponseException(response()->json([ 'status' => false, 'validation_status' => 'jquery_validation', 'all_message' => $validator->errors(), 'event' => 'validation', 'required_field' => $required_field, 'message' => $validator->errors()->first() ]));
        } else {
            throw new HttpResponseException(redirect()->back()->withInput()->with('errors', $validator->errors()));
        }
    }
}
