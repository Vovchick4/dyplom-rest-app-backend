<?php

/**
 * @OA\Schema(
 *   title="Admin Order",
 *   schema="AdminOrderSchema",
 *   description="Admin Order model",
 *   required={"id"},
 *   allOf={
 *      @OA\Schema(
 *          @OA\Property(
 *              property="id",
 *              description="Admin Order id",
 *              type="string",
 *              example="25",
 *          ),
 *          @OA\Property(
 *               property="price",
 *               description="Admin Order price",
 *               type="string",
 *               example="105.45",
 *          ),
 *          @OA\Property(
 *              property="payment_method",
 *              description="Client Order payment_method",
 *              type="string",
 *              example="paypal",
 *              enum={"cash", "paypal", "google", "apple"}
 *          ),
 *      ),
 *      @OA\Schema(ref="#/components/schemas/AdminOrderUpdateSchema"),
 *      @OA\Schema(
 *          @OA\Property(
 *               property="plates",
 *               description="Admin Order plates",
 *               type="array",
 *               example = {
 *                  {
 *                  "id": "25",
 *                  "category_id": "10",
 *                  "name": "Avocado plate",
 *                  "description": "Tasty avocado",
 *                  "image": "avocado.jpg",
 *                  "active": "0",
 *                  "quantity": "10",
 *                  "weight": "108g",
 *                  "price": "43.55",
 *                  "amount": "54",
 *                  "comment": "tasty dish"
 *                  }
 *              },
 *              @OA\Items(ref="#/components/schemas/AdminPlateSchema")
 *          ),
 *     ),
 *   },
 * )
 */

