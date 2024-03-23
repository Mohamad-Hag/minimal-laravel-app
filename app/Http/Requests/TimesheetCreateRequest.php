<?php

namespace App\Http\Requests;

use App\Utils\ResponseUtils;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TimesheetCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {        
        return [
            'task_name' =>'required|string',
            'date' => 'required|date',
            'hours' =>'required|numeric|min:1',
            'user_id' => 'numeric|integer|required|min:1',            
            'project_id' => 'numeric|integer|min:1|required',            
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = ResponseUtils::build(400, $validator->errors(), true);

        throw new HttpResponseException(response()->json($response));
    }
}
