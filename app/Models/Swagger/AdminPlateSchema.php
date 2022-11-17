<?php

/**
 * @OA\Schema(
 *   title="Admin Plate",
 *   schema="AdminPlateSchema",
 *   description="Admin Plate model",
 *   required={"id", "name", "image"},
 *   allOf={
 *      @OA\Schema(
 *          @OA\Property(
 *              property="id",
 *              description="Admin Plate id",
 *              type="string",
 *              example="25",
 *          ),
 *         @OA\Property(
 *              property="price",
 *              description="Admin Plate price",
 *              type="string",
 *              example="25.27",
 *          ),
 *         @OA\Property(
 *              property="amount",
 *              description="Admin Plate amount",
 *              type="string",
 *              example="24",
 *          ),
 *         @OA\Property(
 *              property="comment",
 *              description="Admin Plate comment",
 *              type="string",
 *              example="very spicy dish",
 *          ),
 *     ),
 *     @OA\Schema(ref="#/components/schemas/AdminPlateCreateSchema")
 *  }
 * )
 */

