<?php

namespace App\Http\Requests\Api\Admin;

use App\Models\Plate;
use Illuminate\Foundation\Http\FormRequest;
use Validator;

class PlateUpdateRequest extends FormRequest
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
        $name = request()->name;

        $nameHasBeenChanged = request()->plate->name !== $name;

        $nameFieldRules = [
            'string',
            'min:2',
            'max:256',
        ];

        $restaurantId = request()->user()->restaurant_id;

        if (
            request()->user()->role == 'super-admin'
            && !empty(request()->header('restaurant'))
        ) {
            $restaurantId = request()->header('restaurant');
        }

        if($nameHasBeenChanged) {
            Validator::extend('custom_unique', function () use ($restaurantId) {
                $query = Plate::where('restaurant_id', $restaurantId)
                    ->whereHas('translations', function ($query) {
                        return $query->where('name', request()['name:en'])
                            ->where('locale', 'en');
                    });

                // True means pass, false means fail validation.
                // If count is 0, that means the unique constraint passes.
                return !$query->count();
            });

            $nameFieldRules[] = 'custom_unique';
        }

        return [
            'name:en' => $nameFieldRules,
            'image' => 'file|mimes:jpg,jpeg,png|max:20480',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|integer',
            'active' => 'in:0,1',
        ];
    }
}
