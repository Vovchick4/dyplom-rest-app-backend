<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\TableStoreRequest;
use App\Http\Resources\TableResource;
use App\Models\Table;
use Illuminate\Http\JsonResponse;

class TableController extends Controller
{
    /**
     * Create new table.
     *
     * @OA\Post(
     *   path="/api/client/table",
     *   description="Add table. Uses to add new table.",
     *   tags={"Client Table"},
     *   security={{"passport":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       @OA\Schema(ref="#/components/schemas/AdminTableCreateSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Create new table",
     *     @OA\JsonContent(ref="#/components/schemas/AdminTableSchema"),
     *   ),
     * )
     * @param TableStoreRequest $request
     * @return JsonResponse
     */
    public function store(TableStoreRequest $request): JsonResponse
    {
        $attributes = $request->all();
        $table = Table::create($attributes);
        $data = new TableResource($table);
        return response()->json(['data' => $data, 'status' => 201, 'message' => __('messages.table_created')], 201);
    }
}
