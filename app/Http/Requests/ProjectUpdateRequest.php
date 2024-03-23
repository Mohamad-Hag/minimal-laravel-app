<?php

namespace App\Http\Requests;

use App\Utils\ResponseUtils;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {        
        return [
            'name' =>'string|min:2',
            'department' => 'string|min:2',
            'start_date' =>'date',
            'end_date' => 'date',
            'status' => 'in:active,inactive,completed',            
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = ResponseUtils::build(400, $validator->errors(), true);

        throw new HttpResponseException(response()->json($response));
    }
}
