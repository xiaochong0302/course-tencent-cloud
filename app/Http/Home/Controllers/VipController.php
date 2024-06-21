<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Services\Logic\Vip\CourseList as VipCourseListService;
use App\Services\Logic\Vip\OptionList as VipOptionListService;
use App\Services\Logic\Vip\UserList as VipUserListService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/vip")
 */
class VipController extends Controller
{

    /**
     * @Get("/", name="home.vip.index")
     */
    public function indexAction()
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getVipIndexUrl();
            return $this->response->redirect($location);
        }

        $service = new VipOptionListService();

        $vipOptions = $service->handle();

        $this->seo->prependTitle('会员');

        $this->view->setVar('vip_options', $vipOptions);
    }

    /**
     * @Get("/courses", name="home.vip.courses")
     */
    public function coursesAction()
    {
        $type = $this->request->getQuery('type', 'string', 'discount');

        $service = new VipCourseListService();

        $pager = $service->handle($type);

        $pager->target = "tab-{$type}-courses";

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/users", name="home.vip.users")
     */
    public function usersAction()
    {
        $service = new VipUserListService();

        $pager = $service->handle();

        $pager->target = 'tab-users';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('pager', $pager);
    }

}
