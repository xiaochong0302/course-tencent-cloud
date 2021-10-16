<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/admin")
 */
class PublicController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Get("/auth", name="admin.auth")
     */
    public function authAction()
    {
        $this->response->setStatusCode(401);

        if ($this->request->isAjax()) {
            return $this->jsonError(['msg' => '会话已过期，请重新登录']);
        }

        return $this->response->redirect(['for' => 'admin.login']);
    }

    /**
     * @Get("/forbidden", name="admin.forbidden")
     */
    public function forbiddenAction()
    {
        $this->response->setStatusCode(403);

        if ($this->request->isAjax()) {
            return $this->jsonError(['msg' => '无相关操作权限']);
        }
    }

    /**
     * @Get("/ip2region", name="admin.ip2region")
     */
    public function ip2regionAction()
    {
        $ip = $this->request->getQuery('ip', 'string');

        $region = kg_ip2region($ip);

        $this->view->setVar('region', $region);
    }

    /**
     * @Get("/vod/player", name="admin.vod_player")
     */
    public function vodPlayerAction()
    {
        $chapterId = $this->request->getQuery('chapter_id', 'int');
        $playUrl = $this->request->getQuery('play_url', 'string');

        $this->view->pick('public/vod_player');
        $this->view->setVar('chapter_id', $chapterId);
        $this->view->setVar('play_url', $playUrl);
    }

}
