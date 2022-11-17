<?php

/**
 * @OA\Schema(
 *   title="Admin User",
 *   schema="AdminUserSchema",
 *   description="Admin User model",
 *   required={"id", "name", "role", "email", "restaurant_id"},
 *   allOf={
 *      @OA\Schema(
 *          @OA\Property(
 *              property="id",
 *              description="Admin User id",
 *              type="string",
 *              example="25",
 *          ),
 *     ),
*      @OA\Schema(ref="#/components/schemas/AdminUserCreateSchema")
 *  }
 * )
 */
