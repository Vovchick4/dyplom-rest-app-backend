<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PlateStoreRequest extends FormRequest
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
            'name:en' => [
                'required',
                'string',
                'min:2',
                'max:256',
            ],
            'name:fr' => [
                'required',
                'string',
                'min:2',
                'max:256',
            ],
            'image' => 'required|file|mimes:jpg,jpeg,png|max:20480',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|integer',
            'active' => 'in:0,1',
        ];
    }
}
