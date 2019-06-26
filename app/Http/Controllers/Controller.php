<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
     /**
     * @OA\Info(
     *   title="Example API",
     *   version="1.0",
     *   @OA\Contact(
     *     email="support@example.com",
     *     name="Support Team"
     *   )
     * )
     *  * @OA\SecurityScheme(
     * type="http",
*      securityScheme="bearerAuth",
*      in="header",
*      name="bearerAuth",
*      scheme="bearer",
*      bearerFormat="JWT",
* ),
     */

}
