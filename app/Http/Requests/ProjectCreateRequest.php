<?php

namespace App\Http\Requests;

use App\Utils\ResponseUtils;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {        
        $startDate = strtotime($this->input('start_date'));
        $endDate = strtotime($this->input('end_date'));
        $name = $this->input('name');

        $response = ResponseUtils::build(400, "'end_date' should not be equal or less than 'start_date'", true);

        if ($endDate <= $startDate)  
            throw new HttpResponseException(response()->json($response));

        return [
            'name' => 'required|string|min:2',
            'department' => 'required|string|min:2',
            'start_date' =>'required|date',
            'end_date' => 'required|date',
            'status' => 'required|in:active,inactive,completed',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = ResponseUtils::build(400, $validator->errors(), true);

        throw new HttpResponseException(response()->json($response));
    }
}
