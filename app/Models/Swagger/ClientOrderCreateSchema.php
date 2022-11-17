<?php

/**
 * @OA\Schema(
 *   title="Client Order post/patch",
 *   schema="ClientOrderCreateSchema",
 *   description="Client Order model",
 *   required={""},
 *   @OA\Property(
 *     property="client_id",
 *     description="Client Order client_id",
 *     type="string",
 *     example="25",
 *    ),
 *   @OA\Property(
 *     property="restaurant_id",
 *     description="Client Order restaurant_id",
 *     type="string",
 *     example="1",
 *    ),
 *   @OA\Property(
 *     property="status",
 *     description="Client Order status",
 *     type="string",
 *     example="new",
 *    ),
 *   @OA\Property(
 *     property="payment_status",
 *     description="Client Order payment_status",
 *     type="string",
 *     example="paid",
 *    ),
 *   @OA\Property(
 *     property="table",
 *     description="Client Order table",
 *     type="string",
 *     example="2",
 *    ),
 *   @OA\Property(
 *     property="name",
 *     description="Client Order name",
 *     type="string",
 *     example="Jonh",
 *    ),
 *   @OA\Property(
 *     property="person_quantity",
 *     description="Client Order person_quantity",
 *     type="string",
 *     example="10",
 *    ),
 *   @OA\Property(
 *     property="people_for_quantity",
 *     description="Client Order people_for_quantity",
 *     type="string",
 *     example="5",
 *    ),
 *   @OA\Property(
 *     property="is_takeaway",
 *     description="Client Order is_takeaway",
 *     type="string",
 *     example="0",
 *    ),
 *   @OA\Property(
 *     property="is_online_payment",
 *     description="Client Order is_online_payment",
 *     type="string",
 *     example="1",
 *    ),
 *   @OA\Property(
 *       property="plates",
 *       description="Client Order plate_id",
 *       type="object",
 *       @OA\Property(
 *           property="25",
 *           description="Client Order plate_id",
 *           type="object",
 *           ref="#/components/schemas/PlateOrderSchema",
 *       ),
 *       @OA\Property(
 *           property="36",
 *           description="Client Order plate_id",
 *           type="object",
 *           ref="#/components/schemas/PlateOrderSchema",
 *       ),
 *    ),
 * )
 */

