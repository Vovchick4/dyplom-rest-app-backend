<?php

/**
 * @OA\Schema(
 *   title="Admin Category",
 *   schema="AdminCategorySchema",
 *   description="Admin Category model",
 *   required={"id", "name", "image"},
 *   allOf={
 *     @OA\Schema(
 *      @OA\Property(
 *          property="id",
 *          description="Admin Category id",
 *          type="string",
 *          example="25",
 *      ),
 *      @OA\Property(
 *          property="restaurant_id",
 *          description="Admin Category restaurant_id",
 *          type="string",
 *          example="25",
 *      ),
 *      @OA\Property(
 *          property="link",
 *          description="Admin Category link",
 *          type="string",
 *          example="/url-path-to-category",
 *      ),
 *     ),
 *     @OA\Schema(ref="#/components/schemas/AdminCategoryCreateSchema")
 *   }
 * ),

 */
