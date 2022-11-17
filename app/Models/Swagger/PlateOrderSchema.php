<?php

/**
 * @OA\Schema(
 *   title="Plate Order",
 *   schema="PlateOrderSchema",
 *   type="object",
 *   required={"price", "amount"},
 *   @OA\Property(
 *     property="price",
 *     description="Order plate price",
 *     type="string",
 *     example="45.47"
 *   ),
 *   @OA\Property(
 *     property="amount",
 *     description="Order plate amount",
 *     type="string",
 *     example="2"
 *   ),
 *   @OA\Property(
 *     property="comment",
 *     description="Order plate comment",
 *     type="string",
 *     example="with apple"
 *   ),
 * ),
 */
