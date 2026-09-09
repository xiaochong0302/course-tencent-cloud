<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\User\Console\AccountInfo as AccountInfoService;
use App\Services\Logic\User\Console\FavoriteList as FavoriteListService;
use App\Services\Logic\User\Console\Online as OnlineService;
use App\Services\Logic\User\Console\OrderList as OrderListService;
use App\Services\Logic\User\Console\ProfileInfo as ProfileInfoService;
use App\Services\Logic\User\Console\ProfileUpdate as ProfileUpdateService;
use App\Services\Logic\User\Console\RefundList as RefundListService;
use App\Services\Logic\User\Console\ReviewList as ReviewListService;
use App\Services\Logic\User\Console\StudyCourseList as StudyCourseListService;
use App\Services\Logic\User\OnlineStat as OnlineStatService;
use App\Services\Logic\User\UserInfo as UserInfoService;
use Phalcon\Mvc\Dispatcher;

/**
 * @RoutePrefix("/uc")
 */
class UserConsoleController extends Controller
{

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        parent::beforeExecuteRoute($dispatcher);

        if ($this->authUser->id == 0) {
            $dispatcher->forward([
                'controller' => 'account',
                'action' => 'login',
            ]);
            return false;
        }

        return true;
    }

    public function initialize()
    {
        parent::initialize();

        $authUser = $this->getLoginUser();

        $this->seo->prependTitle('用户中心');

        $this->view->setVar('auth_user', $authUser);
    }

    /**
     * @Get("/", name="home.uc.index")
     */
    public function indexAction()
    {
        $service = new UserInfoService();
        $user = $service->handle($this->authUser->id);

        $service = new OnlineStatService();
        $onlineStats = $service->handle($this->authUser->id);

        $this->view->pick('user/console/index');
        $this->view->setVar('user', $user);
        $this->view->setVar('online_stats', $onlineStats);
    }

    /**
     * @Get("/profile", name="home.uc.profile")
     */
    public function profileAction()
    {
        $service = new ProfileInfoService();

        $user = $service->handle();

        $this->view->pick('user/console/profile');
        $this->view->setVar('user', $user);
    }

    /**
     * @Get("/account", name="home.uc.account")
     */
    public function accountAction()
    {
        $type = $this->request->getQuery('type', 'string', 'info');

        $service = new AccountInfoService();

        $account = $service->handle();

        if ($type == 'info') {
            $this->view->pick('user/console/account_info');
        } elseif ($type == 'phone') {
            $this->view->pick('user/console/account_phone');
        } elseif ($type == 'email') {
            $this->view->pick('user/console/account_email');
        } elseif ($type == 'password') {
            $this->view->pick('user/console/account_password');
        }

        $this->view->setVar('account', $account);
    }

    /**
     * @Get("/study-courses", name="home.uc.study_courses")
     */
    public function studyCoursesAction()
    {
        $service = new StudyCourseListService();

        $pager = $service->handle();

        $this->view->pick('user/console/study_courses');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/favorites", name="home.uc.favorites")
     */
    public function favoritesAction()
    {
        $service = new FavoriteListService();

        $pager = $service->handle();

        $this->view->pick('user/console/favorites');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/reviews", name="home.uc.reviews")
     */
    public function reviewsAction()
    {
        $service = new ReviewListService();

        $pager = $service->handle();

        $this->view->pick('user/console/reviews');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/orders", name="home.uc.orders")
     */
    public function ordersAction()
    {
        $service = new OrderListService();

        $pager = $service->handle();

        $this->view->pick('user/console/orders');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/refunds", name="home.uc.refunds")
     */
    public function refundsAction()
    {
        $service = new RefundListService();

        $pager = $service->handle();

        $this->view->pick('user/console/refunds');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/profile/update", name="home.uc.update_profile")
     */
    public function updateProfileAction()
    {
        $service = new ProfileUpdateService();

        $service->handle();

        $location = $this->url->get(['for' => 'home.uc.profile']);

        $content = [
            'location' => $location,
            'msg' => '更新资料成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/online", name="home.uc.online")
     */
    public function onlineAction()
    {
        $service = new OnlineService();

        $service->handle();

        return $this->jsonSuccess();
    }

}
