<?php

/**
 * @OA\Schema(
 *   title="Client",
 *   schema="ClientSchema",
 *   description="Client model",
 *   required={"id", "name", "email", "phone", "password"},
 *   @OA\Property(
 *     property="id",
 *     description="Client id",
 *     type="string",
 *     example="25",
 *     ),
 *   ref="#/components/schemas/ClientUpdateSchema"
 * )
 */
