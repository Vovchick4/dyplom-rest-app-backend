<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class ClientUpdateRequest extends FormRequest
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
            'name' => 'string|min:2|max:256',
            'phone' => 'string|min:8|max:15|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            // 'email' => 'email|unique:users,email',
            'password' => 'string|min:6|max:20',
        ];
    }
}
