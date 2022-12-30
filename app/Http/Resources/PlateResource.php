<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlateResource extends JsonResource
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
            'name' => $this->name ?? '',
            'name:en' => $this->translate('en')->name ?? '',
            'name:fr' => $this->translate('fr')->name ?? '',
            'description' => $this->description ?? '',
            'description:en' => $this->translate('en')->description ?? '',
            'description:fr' => $this->translate('fr')->description ?? '',
            'image' => url("images/{$this->image}"),
            'active' => $this->active,
            'quantity' => $this->quantity,
            'weight' => $this->weight,
            'category_id' => $this->categories->pluck('id'),
            'restaurant_id' => $this->restaurant_id,
            'checked' => isset($request->category) ?? $this->categories->pluck('id')->contains($request->category->id) ? true : false,
            'price' => $this->pivot ? $this->pivot->price : $this->price,
            'amount' => $this->when($this->pivot, function () {
                return $this->pivot->amount;
            }),
            'comment' => $this->when($this->pivot, function () {
                return $this->pivot->comment;
            })
        ];
    }
}
