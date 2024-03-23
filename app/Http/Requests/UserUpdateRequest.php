<?php

namespace App\Http\Requests;

use App\Utils\ResponseUtils;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
{    
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {        
        return [
            'first_name' =>'string',
            'last_name' => 'string',
            'email' =>'email|unique:users,email,'.$this->id,
            'password' => 'string|min:6',
            'gender' => 'in:male,female',
            'date_of_birth' => 'date'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = ResponseUtils::build(400, $validator->errors(), true);

        throw new HttpResponseException(response()->json($response));
    }
}
