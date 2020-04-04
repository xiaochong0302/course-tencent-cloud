<?php

namespace App\Http\Admin\Controllers;

use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/admin")
 */
class PublicController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Route("/auth", name="admin.auth")
     */
    public function authAction()
    {
        $isAjaxRequest = is_ajax_request();

        if ($isAjaxRequest) {
            return $this->jsonError(['msg' => '会话已过期，请重新登录']);
        }

        $this->response->redirect(['for' => 'admin.login']);
    }

    /**
     * @Route("/robot", name="admin.robot")
     */
    public function robotAction()
    {
        $isAjaxRequest = is_ajax_request();

        if ($isAjaxRequest) {
            return $this->jsonError(['msg' => '疑似机器人请求']);
        }
    }

    /**
     * @Route("/forbidden", name="admin.forbidden")
     */
    public function forbiddenAction()
    {
        $isAjaxRequest = is_ajax_request();

        if ($isAjaxRequest) {
            return $this->jsonError(['msg' => '无相关操作权限']);
        }
    }

    /**
     * @Route("/ip2region", name="admin.ip2region")
     */
    public function ip2regionAction()
    {
        $ip = $this->request->getQuery('ip', 'trim');

        $region = kg_ip2region($ip);

        $this->view->setVar('region', $region);
    }

}
