<?php

namespace App\Http\Requests\Api\Admin;

use App\Models\Plate;
use Illuminate\Foundation\Http\FormRequest;
use Validator;

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
        Validator::extend('custom_unique', function ($attribute, $value) {
            $query = Plate::where('restaurant_id', request()->user()->restaurant_id)
                ->whereHas('translations', function ($query) use ($attribute, $value) {
                    return $query->where('name', $value)
                        ->where('locale', substr(strstr($attribute, ':'), 1, strlen($attribute)));
                });

            // True means pass, false means fail validation.
            // If count is 0, that means the unique constraint passes.
            return !$query->count();
        });

        return [
            'name:en' => [
                'required',
                'string',
                'min:2',
                'max:256',
                'custom_unique'
            ],
            'name:fr' => [
                'required',
                'string',
                'min:2',
                'max:256',
                'custom_unique'
            ],
            'image' => 'required|file|mimes:jpg,jpeg,png|max:20480',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|integer',
            'active' => 'in:0,1',
        ];
    }
}
