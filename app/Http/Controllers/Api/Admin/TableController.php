<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Table;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\TableIndexRequest;
use App\Http\Resources\TableCollection;
use Illuminate\Http\JsonResponse;

class TableController extends Controller
{
    /**
     * Index table.
     *
     * @OA\GET(
     *   path="/api/client/table",
     *   description="Index table. Uses to index table.",
     *   tags={"Client Table"},
     *   security={{"passport":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       @OA\Schema(ref="#/components/schemas/AdminTableIndexSchema"),
     *     ),
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Index table",
     *     @OA\JsonContent(ref="#/components/schemas/AdminTableSchema"),
     *   ),
     * )
     * @param TableIndexRequest $request
     * @return JsonResponse
     */
    public function index(TableIndexRequest $request): JsonResponse
    {
        $table = Table::where("rest_id", $request->header("rest_id"));
        $res = new TableCollection($table);
        $data = $res->response()->getData(true);
        $data['status'] = 200;
        $data['message'] = __('messages.ok');
        return response()->json($data);
    }
}
