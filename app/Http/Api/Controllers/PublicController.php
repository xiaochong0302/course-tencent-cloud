<?php

namespace App\Http\Api\Controllers;

use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/api")
 */
class PublicController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

}
