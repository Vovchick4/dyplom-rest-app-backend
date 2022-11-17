<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PlateResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'client_id' => $this->client_id,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'table' => $this->table,
            'person_quantity' => $this->person_quantity,
            'people_for_quantity' => $this->people_for_quantity,
            'is_takeaway' => $this->is_takeaway,
            'is_online_payment' => $this->is_online_payment,
            'price' => $this->price,
            'plates' => PlateResource::collection($this->plates()->withTrashed()->get()),
            'created_at' => $this->created_at->timestamp,
        ];
    }
}
