<?php

namespace App\Http\Home\Controllers;

use App\Services\Logic\User\Console\AccountInfo as AccountInfoService;
use App\Services\Logic\User\Console\ConsultList as ConsultListService;
use App\Services\Logic\User\Console\CourseList as CourseListService;
use App\Services\Logic\User\Console\FavoriteList as FavoriteListService;
use App\Services\Logic\User\Console\FriendList as FriendListService;
use App\Services\Logic\User\Console\GroupList as GroupListService;
use App\Services\Logic\User\Console\OrderList as OrderListService;
use App\Services\Logic\User\Console\ProfileInfo as ProfileInfoService;
use App\Services\Logic\User\Console\ProfileUpdate as ProfileUpdateService;
use App\Services\Logic\User\Console\RefundList as RefundListService;
use App\Services\Logic\User\Console\ReviewList as ReviewListService;
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
            $this->response->redirect(['for' => 'home.account.login']);
            return false;
        }

        return true;
    }

    /**
     * @Get("/index", name="home.uc.index")
     */
    public function indexAction()
    {
        $this->dispatcher->forward(['action' => 'courses']);
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
        $service = new AccountInfoService();

        $account = $service->handle();

        $this->view->pick('user/console/account');
        $this->view->setVar('account', $account);
    }

    /**
     * @Get("/courses", name="home.uc.courses")
     */
    public function coursesAction()
    {
        $service = new CourseListService();

        $pager = $service->handle();

        $this->view->pick('user/console/courses');
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
     * @Get("/consults", name="home.uc.consults")
     */
    public function consultsAction()
    {
        $service = new ConsultListService();

        $pager = $service->handle();

        $this->view->pick('user/console/consults');
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
     * @Get("/friends", name="home.uc.friends")
     */
    public function friendsAction()
    {
        $service = new FriendListService();

        $pager = $service->handle();

        $this->view->pick('user/console/friends');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/groups", name="home.uc.groups")
     */
    public function groupsAction()
    {
        $scope = $this->request->getQuery('scope', 'string', 'joined');

        $service = new GroupListService();

        $pager = $service->handle($scope);

        $this->view->pick('user/console/groups');
        $this->view->setVar('scope', $scope);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/profile/update", name="home.uc.update_profile")
     */
    public function updateProfileAction()
    {
        $service = new ProfileUpdateService();

        $service->handle();

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => '更新资料成功',
        ];

        return $this->jsonSuccess($content);
    }

}
