<?php

/**
 * @OA\Schema(
 *   title="Admin User post/patch",
 *   schema="AdminUserCreateSchema",
 *   description="Admin User model",
 *   required={"name", "role", "email", "password", "restaurant_id"},
 *   @OA\Property(
 *     property="name",
 *     description="Admin User name",
 *     type="string",
 *     example="John",
 *     ),
 *   @OA\Property(
 *     property="role",
 *     description="Admin User role",
 *     type="string",
 *     enum={"owner", "admin"},
 *     example="owner",
 *     ),
 *   @OA\Property(
 *     property="email",
 *     description="Admin User email",
 *     type="string",
 *     example="test@gmail.com",
 *     ),
 *    @OA\Property(
 *     property="restaurant_id",
 *     description="Admin User restaurant_id",
 *     type="string",
 *     example="2",
 *     ),
 *    @OA\Property(
 *     property="lastname",
 *     description="Admin User lastname",
 *     type="string",
 *     example="Smith",
 *     ),
 *   @OA\Property(
 *     property="image",
 *     description="Admin User image",
 *     type="string",
 *     example="image.jpg",
 *     ),
 * )
 */
