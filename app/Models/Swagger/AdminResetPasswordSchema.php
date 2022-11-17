<?php

/**
 * @OA\Schema(
 *   title="Admin Reset Password",
 *   schema="AdminResetPasswordSchema",
 *   description="Admin Reset Password request",
 *   required={"email"},
 *   @OA\Property(
 *     property="email",
 *     description="Admin Reset Password email",
 *     type="string",
 *     example="test@gmail.com",
 *     ),
 * )
 */
