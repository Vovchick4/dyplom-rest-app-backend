<?php

/**
 * @OA\Schema(
 *   title="Admin Order patch",
 *   schema="AdminOrderUpdateSchema",
 *   description="Admin Order update model",
 *   required={""},
 *   @OA\Property(
 *     property="status",
 *     description="Admin Order status",
 *     type="string",
 *     enum={"new", "viewed", "in_process", "completed", "canceled"},
 *     example="new",
 *    ),
 *   @OA\Property(
 *     property="payment_status",
 *     description="Admin Order payment_status",
 *     type="string",
 *     enum={"pending", "paid", "not_paid"},
 *     example="paid",
 *    ),
 * )
 */

