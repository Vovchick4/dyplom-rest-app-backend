<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderIndexRequest extends FormRequest
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
            'client_id' => 'nullable|integer|exists:clients,id',
            'status' => 'in:new,viewed,in_process,completed,canceled',
            'payment_status' => 'in:pending,paid,not_paid',
        ];
    }
}
