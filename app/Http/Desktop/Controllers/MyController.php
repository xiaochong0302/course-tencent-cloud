<?php

namespace App\Http\Desktop\Controllers;

use App\Services\Frontend\My\AccountInfo as AccountInfoService;
use App\Services\Frontend\My\ConsultList as MyConsultListService;
use App\Services\Frontend\My\CourseList as MyCourseListService;
use App\Services\Frontend\My\FavoriteList as MyFavoriteListService;
use App\Services\Frontend\My\FriendList as MyFriendListService;
use App\Services\Frontend\My\GroupList as MyGroupListService;
use App\Services\Frontend\My\OrderList as MyOrderListService;
use App\Services\Frontend\My\ProfileInfo as ProfileInfoService;
use App\Services\Frontend\My\ProfileUpdate as ProfileUpdateService;
use App\Services\Frontend\My\RefundList as MyRefundListService;
use App\Services\Frontend\My\ReviewList as MyReviewListService;

/**
 * @RoutePrefix("/my")
 */
class MyController extends Controller
{

    public function initialize()
    {
        parent::initialize();

        if ($this->authUser->id == 0) {
            $this->response->redirect(['for' => 'desktop.account.login']);
        }
    }

    /**
     * @Get("/", name="desktop.my.index")
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(['action' => 'courses']);
    }

    /**
     * @Get("/profile", name="desktop.my.profile")
     */
    public function profileAction()
    {
        $service = new ProfileInfoService();

        $user = $service->handle();

        $this->view->setVar('user', $user);
    }

    /**
     * @Get("/account", name="desktop.my.account")
     */
    public function accountAction()
    {
        $service = new AccountInfoService();

        $account = $service->handle();

        $this->view->setVar('account', $account);
    }

    /**
     * @Get("/courses", name="desktop.my.courses")
     */
    public function coursesAction()
    {
        $service = new MyCourseListService();

        $pager = $service->handle();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/favorites", name="desktop.my.favorites")
     */
    public function favoritesAction()
    {
        $service = new MyFavoriteListService();

        $pager = $service->handle();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/consults", name="desktop.my.consults")
     */
    public function consultsAction()
    {
        $service = new MyConsultListService();

        $pager = $service->handle();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/reviews", name="desktop.my.reviews")
     */
    public function reviewsAction()
    {
        $service = new MyReviewListService();

        $pager = $service->handle();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/orders", name="desktop.my.orders")
     */
    public function ordersAction()
    {
        $service = new MyOrderListService();

        $pager = $service->handle();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/refunds", name="desktop.my.refunds")
     */
    public function refundsAction()
    {
        $service = new MyRefundListService();

        $pager = $service->handle();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/friends", name="desktop.my.friends")
     */
    public function friendsAction()
    {
        $service = new MyFriendListService();

        $pager = $service->handle();

        $this->view->pick('my/friends');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/groups", name="desktop.my.groups")
     */
    public function groupsAction()
    {
        $type = $this->request->getQuery('type', 'trim', 'joined');

        $service = new MyGroupListService();

        $pager = $service->handle($type);

        $this->view->pick('my/groups');
        $this->view->setVar('type', $type);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/profile/update", name="desktop.my.update_profile")
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
