<?php

/**
 * @OA\Schema(
 *   title="Client Reset Password",
 *   schema="ClientResetPasswordSchema",
 *   description="Client Reset Password request",
 *   required={"email"},
 *   @OA\Property(
 *     property="email",
 *     description="Client Reset Password email",
 *     type="string",
 *     example="test@gmail.com",
 *     ),
 * )
 */
