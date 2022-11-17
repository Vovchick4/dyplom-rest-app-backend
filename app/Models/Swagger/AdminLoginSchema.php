<?php

/**
 * @OA\Schema(
 *   title="Admin Login",
 *   schema="AdminLoginSchema",
 *   description="Admin Login request",
 *   required={"email", "password"},
 *   @OA\Property(
 *     property="email",
 *     description="Admin Login email",
 *     type="string",
 *     example="test@gmail.com",
 *     ),
 *   @OA\Property(
 *     property="password",
 *     description="Admin Login password",
 *     type="string",
 *     example="ygyf7Gty!@@#",
 *     ),
 *   @OA\Property(
 *     property="remember_me",
 *     description="Admin Login remember_me",
 *     type="string",
 *     enum={"0", "1"},
 *     example="1",
 *     ),
 * )
 */
