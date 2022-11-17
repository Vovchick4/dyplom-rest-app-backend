<?php

namespace App\Http\Requests\Api\Client;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
            'is_takeaway' => 'in:0,1',
            'is_online_payment' => 'in:0,1',
            'restaurant_id' => 'required|integer|exists:restaurants,id',
            'person_quantity' => 'nullable|integer',
            'people_for_quantity' => 'nullable|integer',
            'table' => 'required',
            'plates' => 'required|array'
            //TODO: check in plates required 'price, amount', nullable|string 'comment'
        ];
    }
}
