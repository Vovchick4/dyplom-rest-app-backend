<?php

/**
 * @OA\Schema(
 *   title="Client Order",
 *   schema="ClientOrderSchema",
 *   description="Client Order model",
 *   required={"id"},
 *   @OA\Property(
 *     property="id",
 *     description="Client Order id",
 *     type="string",
 *     example="25",
 *    ),
 *   @OA\Property(
 *     property="client_id",
 *     description="Client Order client_id",
 *     type="string",
 *     example="25",
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
 *    @OA\Property(
 *     property="payment_method",
 *     description="Client Order payment_method",
 *     type="string",
 *     example="paypal",
 *     enum={"cash", "paypal", "google", "apple"}
 *    ),
 *   @OA\Property(
 *     property="price",
 *     description="Client Order price",
 *     type="string",
 *     example="105.45",
 *    ),
 *   @OA\Property(
 *     property="plates",
 *     description="Client Order plates",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/AdminPlateSchema")
 *
 *    ),
 * )
 */

