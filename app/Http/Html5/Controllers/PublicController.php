<?php

namespace App\Http\Html5\Controllers;

use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/html5")
 */
class PublicController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Get("/throttle", name="html5.throttle")
     */
    public function throttleAction()
    {
        return $this->jsonError(['msg' => '请求过于频繁']);
    }

}
