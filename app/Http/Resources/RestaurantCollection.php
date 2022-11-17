<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RestaurantCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\RestaurantResource';

    private $total;
    private $last_page;
    private $current_page;
    private $per_page;

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->total,
                'current_page' => $this->current_page,
                'per_page' => $this->per_page,
                'last_page' => $this->last_page
            ]
        ];
    }

    public function __construct($resource)
    {
        $this->total = $resource->total();
        $this->last_page = $resource->lastPage();
        $this->current_page = $resource->currentPage();
        $this->per_page = $resource->perPage();

        $resource = $resource->getCollection();

        parent::__construct($resource);
    }
}
