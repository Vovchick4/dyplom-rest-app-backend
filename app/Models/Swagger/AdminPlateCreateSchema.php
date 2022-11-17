<?php

/**
 * @OA\Schema(
 *   title="Admin Plate post/patch",
 *   schema="AdminPlateCreateSchema",
 *   description="Admin Plate model",
 *   required={"name", "image"},
 *   @OA\Property(
 *     property="category_id",
 *     description="Admin Plate category_id",
 *     type="string",
 *     example="10",
 *   ),
 *   @OA\Property(
 *     property="name",
 *     description="Admin Plate name",
 *     type="string",
 *     example="Avocado plate",
 *   ),
 *   @OA\Property(
 *     property="description",
 *     description="Admin Plate description",
 *     type="string",
 *     example="Tasty avocado",
 *   ),
 *   @OA\Property(
 *     property="image",
 *     description="Admin Plate image",
 *     type="file",
 *   ),
 *   @OA\Property(
 *     property="active",
 *     description="Admin Plate status active",
 *     type="string",
 *     example="0",
 *   ),
 *   @OA\Property(
 *     property="quantity",
 *     description="Admin Plate quantity",
 *     type="string",
 *     example="10",
 *   ),
 *   @OA\Property(
 *     property="weight",
 *     description="Admin Plate weight",
 *     type="string",
 *     example="108g",
 *   ),
 * )
 */

