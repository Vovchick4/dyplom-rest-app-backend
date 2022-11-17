<?php

/**
 * @OA\Schema(
 *   title="Admin Register post",
 *   schema="AdminRegisterCreateSchema",
 *   description="Admin Register request",
 *   required={"name", "phone", "restaurant_id", "email", "password"},
 *   @OA\Property(
 *     property="name",
 *     description="Admin User name",
 *     type="string",
 *     example="John",
 *     ),
 *   @OA\Property(
 *     property="phone",
 *     description="Admin User phone",
 *     type="string",
 *     example="88848455555",
 *     ),
 *   @OA\Property(
 *     property="email",
 *     description="Admin User email",
 *     type="string",
 *     example="test@gmail.com",
 *     ),
 *    @OA\Property(
 *     property="restaurant_name",
 *     description="New restaurant name",
 *     type="string",
 *     example="Restaurant",
 *     ),
 *    @OA\Property(
 *     property="password",
 *     description="Admin User password",
 *     type="string",
 *     example="f45t4g$%^cf",
 *     ),
 *   @OA\Property(
 *     property="address",
 *     description="Admin User address",
 *     type="string",
 *     example="New York",
 *     ),
 *   @OA\Property(
 *     property="lastname",
 *     description="Admin User lastname",
 *     type="string",
 *     example="Smith",
 *     ),
 *   @OA\Property(
 *     property="image",
 *     description="Admin User image",
 *     type="file",
 *     ),
 * )
 */
