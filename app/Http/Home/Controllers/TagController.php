<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Tag\TagFollow as TagFollowService;
use App\Services\Logic\Tag\TagList as TagListService;
use App\Services\Logic\Tag\FollowList as FollowListService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/tag")
 */
class TagController extends Controller
{

    /**
     * @Get("/list", name="home.tag.list")
     */
    public function listAction()
    {
        $this->seo->prependTitle('标签');
    }

    /**
     * @Get("/list/pager", name="home.tag.list_pager")
     */
    public function listPagerAction()
    {
        $service = new TagListService();

        $pager = $service->handle();

        $pager->target = 'all-tag-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('tag/list_pager');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/my/pager", name="home.tag.my_pager")
     */
    public function myPagerAction()
    {
        $service = new FollowListService();

        $pager = $service->handle();

        $pager->target = 'my-tag-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('tag/my_pager');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/{id:[0-9]+}/follow", name="home.tag.follow")
     */
    public function followAction($id)
    {
        $service = new TagFollowService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '关注成功' : '取消关注成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

}
