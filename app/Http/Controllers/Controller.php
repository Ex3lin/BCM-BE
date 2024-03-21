<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    version="1.0.0",
 *    title="Ex3 BCM API",
 *    description="BCM",
 *    version="1.0.0",
 * )
 * 
 * @OA\Get(
 *      path="/api/documentation",
 *      description="BCM Swagger",
 *      @OA\Response(response="default", description="welcome to API doc" )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
