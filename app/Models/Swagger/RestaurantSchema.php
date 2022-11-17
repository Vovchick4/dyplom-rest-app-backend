<?php

/**
 * @OA\Schema(
 *   title="Restaurant",
 *   schema="RestaurantSchema",
 *   description="Restaurant model",
 *   required={"id", "name", "email", "phone", "password"},
 *   @OA\Property(
 *     property="id",
 *     description="Restaurant id",
 *     type="string",
 *     example="25",
 *     ),
 *   @OA\Property(
 *     property="name",
 *     description="Restaurant name",
 *     type="string",
 *     example="Pizza Hut",
 *     ),
 *   @OA\Property(
 *     property="email",
 *     description="Restaurant address",
 *     type="string",
 *     example="109 Ferry Skyway Babyberg, WV 52845",
 *     ),
 *   @OA\Property(
 *     property="phone",
 *     description="Restaurant phone",
 *     type="string",
 *     example="545345435434",
 *     ),
 *   @OA\Property(
 *     property="logo",
 *     description="Restaurant logo",
 *     type="string",
 *     example="http://restaurant/images/logo.jpeg",
 *     ),
 *    @OA\Property(
 *     property="slug",
 *     description="Restaurant slug",
 *     type="string",
 *     example="sequi",
 *     ),
 * )
 */
