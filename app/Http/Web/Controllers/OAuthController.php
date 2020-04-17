<?php

namespace App\Http\Web\Controllers;

use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/oauth")
 */
class OAuthController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Get("/qq/connect", name="web.oauth.qq.connect")
     */
    public function qqConnectAction()
    {

    }

    /**
     * @Get("/qq/callback", name="web.oauth.qq.callback")
     */
    public function qqCallbackAction()
    {

    }

    /**
     * @Get("/weibo/connect", name="web.oauth.weibo.connect")
     */
    public function weiboConnectAction()
    {

    }

    /**
     * @Get("/weibo/callback", name="web.oauth.weibo.callback")
     */
    public function weiboCallbackAction()
    {

    }

    /**
     * @Get("/weixin/connect", name="web.oauth.weixin.connect")
     */
    public function weixinConnectAction()
    {

    }

    /**
     * @Get("/weixin/callback", name="web.oauth.weixin.callback")
     */
    public function weixinCallbackAction()
    {

    }

}
