<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TableCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\TableResource';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }

    public function __construct($resource)
    {
        $resource = $resource->getCollection();

        parent::__construct($resource);
    }
}
