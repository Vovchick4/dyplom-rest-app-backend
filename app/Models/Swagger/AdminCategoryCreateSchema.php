<?php

/**
 * @OA\Schema(
 *   title="Admin Category post/patch",
 *   schema="AdminCategoryCreateSchema",
 *   description="Admin Category model",
 *   required={"name", "image"},
 *   @OA\Property(
 *     property="parent_id",
 *     description="Admin Category parent_id",
 *     type="string",
 *     example="20",
 *     ),
 *   @OA\Property(
 *     property="name",
 *     description="Admin Category name",
 *     type="string",
 *     example="Food",
 *     ),
 *   @OA\Property(
 *     property="image",
 *     description="Admin Category image",
 *     type="file",
 *     ),
 *   @OA\Property(
 *     property="active",
 *     description="Admin Category status active",
 *     type="string",
 *     example="1",
 *     ),
 * )
 */
