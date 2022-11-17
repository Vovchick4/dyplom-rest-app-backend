<?php

namespace App\Http\Requests\Api\Client\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
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
        return [
            'name' => 'required|string|min:2|max:256',
            'phone' => 'required_without:email|string|max:15|unique:clients,phone',
            'email' => 'required_without:phone|email|unique:clients,email',
            'password' => 'required|string|min:6|max:20',
        ];
    }

    /**
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'status' => 422,
            'data' => [],
            'message' => __('validation.data_invalid'),
            'errors' => $validator->errors()
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
