<?php

/**
 * @OA\Schema(
 *   title="Client Category",
 *   schema="ClientCategorySchema",
 *   description="Client Category model",
 *   required={"id", "name", "image"},
 *   @OA\Property(
 *     property="id",
 *     description="Client Category id",
 *     type="string",
 *     example="25",
 *     ),
 *   @OA\Property(
 *     property="parent_id",
 *     description="Client Category parent_id",
 *     type="string",
 *     example="20",
 *     ),
 *   @OA\Property(
 *     property="name",
 *     description="Client Category name",
 *     type="string",
 *     example="Food",
 *     ),
 *   @OA\Property(
 *     property="image",
 *     description="Client Category image",
 *     type="string",
 *     example="image.jpg",
 *     ),
 *   @OA\Property(
 *     property="active",
 *     description="Client Category status active",
 *     type="string",
 *     example="1",
 *     ),
 * )
 */
