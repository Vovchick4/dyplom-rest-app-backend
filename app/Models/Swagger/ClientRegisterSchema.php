<?php

/**
 * @OA\Schema(
 *   title="Client Register",
 *   schema="ClientRegisterSchema",
 *   description="Client Register request",
 *   required={"name", "phone", "email", "password"},
 *   @OA\Property(
 *     property="name",
 *     description="Client name",
 *     type="string",
 *     example="John",
 *     ),
 *   @OA\Property(
 *     property="phone",
 *     description="Client phone",
 *     type="string",
 *     example="88844545555",
 *     ),
 *   @OA\Property(
 *     property="email",
 *     description="Client email",
 *     type="string",
 *     example="test@gmail.com",
 *     ),
 *    @OA\Property(
 *     property="password",
 *     description="Client password",
 *     type="string",
 *     example="f45t4g$%^cf",
 *     ),
 * )
 */
