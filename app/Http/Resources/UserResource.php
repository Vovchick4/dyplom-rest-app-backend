<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'lastname' => $this->lastname,
            'image' => $this->image ? url("images/{$this->image}") : null,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'restaurant_id' => $this->restaurant_id,
            'restaurant_slug' => $this->restaurant->slug
        ];
    }
}
