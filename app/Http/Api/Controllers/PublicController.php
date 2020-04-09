<?php

namespace App\Http\Api\Controllers;

use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/api")
 */
class PublicController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Get("/throttle", name="api.throttle")
     */
    public function throttleAction()
    {
        return $this->jsonError(['msg' => '请求过于频繁']);
    }

}
