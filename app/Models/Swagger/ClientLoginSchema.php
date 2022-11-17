<?php

/**
 * @OA\Schema(
 *   title="Client Login",
 *   schema="ClientLoginSchema",
 *   description="Client Login request",
 *   required={"email_or_phone", "password"},
 *   @OA\Property(
 *     property="email_or_phone",
 *     description="Client Login email_or_phone",
 *     type="string",
 *     example="test@gmail.com or 543543534543",
 *     ),
 *   @OA\Property(
 *     property="password",
 *     description="Client Login password",
 *     type="string",
 *     example="ygyf7Gty!@@#",
 *     ),
 * )
 */
