<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
     /**
     * @OA\Info(
     *   title="Job Board API",
     *   version="1.0",
     *   @OA\Contact(
     *     email="michael.basweti@andela.com",
     *     name="Michael Basweti"
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
