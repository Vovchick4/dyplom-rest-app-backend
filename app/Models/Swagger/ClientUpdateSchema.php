<?php

/**
 * @OA\Schema(
 *   title="Client",
 *   schema="ClientUpdateSchema",
 *   description="Client update request",
 *   required={""},
 *   @OA\Property(
 *     property="name",
 *     description="Client name",
 *     type="string",
 *     example="John",
 *     ),
 *   @OA\Property(
 *     property="email",
 *     description="Client email",
 *     type="string",
 *     example="test@gmail.com",
 *     ),
 *   @OA\Property(
 *     property="phone",
 *     description="Client phone",
 *     type="string",
 *     example="545345435434",
 *     ),
 *   @OA\Property(
 *     property="password",
 *     description="Client password",
 *     type="string",
 *     example="YT7ryghg$%d",
 *     ),
 *    @OA\Property(
 *     property="fb_id",
 *     description="Client fb_id",
 *     type="string",
 *     example="fb_544353656565453g4trtgretgt454t4",
 *     ),
 *    @OA\Property(
 *     property="google_id",
 *     description="Client google_id",
 *     type="string",
 *     example="google_544353453g4trtgretgt454t4",
 *     ),
 *   @OA\Property(
 *     property="payment_method",
 *     description="Client payment_method",
 *     type="string",
 *     enum={"cash", "card", "google", "apple"},
 *     example="cash",
 *     ),
 * )
 */
