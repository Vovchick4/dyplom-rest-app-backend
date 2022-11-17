<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'image' => url("images/{$this->image}"),
            'active' => $this->active,
            'parent_id' => $this->parent_id,
            'restaurant_id' => $this->restaurant_id,
            'link' => $this->link,
        ];
    }
}
