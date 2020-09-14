<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     host="urlshortenerphp.test",
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="URLShortenerPHP",
 *         description="Laravel project for generating short URLs for redirection",
 *         @SWG\Contact(
 *             email="tomas.quirino@mecanica.coppe.ufrj.br"
 *         )
 *     )
 * )
 *
 * @SWG\Tag(
 *     name="URL",
 *     description="Endpoints for managing URLs"
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
