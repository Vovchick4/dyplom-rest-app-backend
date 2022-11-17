<?php

/**
 * @OA\Schema(
 *   title="Admin Register",
 *   schema="AdminRegisterSchema",
 *   description="Admin Register request",
 *   required={"name", "phone", "restaurant_id", "email", "password"},
 *   allOf={
 *      @OA\Schema(
 *          @OA\Property(
 *            property="id",
 *            description="Admin User id",
 *            type="string",
 *            example="23",
 *          ),
 *     ),
 *     @OA\Schema(ref="#/components/schemas/AdminRegisterCreateSchema")
 *  }
 * )
 */
