<?php

namespace App\Http\Web\Controllers;

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
            $this->response->redirect(['for' => 'web.account.login']);
        }
    }

    /**
     * @Get("/", name="web.my.index")
     */
    public function indexAction()
    {
        return $this->dispatcher->forward(['action' => 'courses']);
    }

    /**
     * @Get("/profile", name="web.my.profile")
     */
    public function profileAction()
    {
        $service = new ProfileInfoService();

        $user = $service->handle();

        $this->view->setVar('user', $user);
    }

    /**
     * @Get("/account", name="web.my.account")
     */
    public function accountAction()
    {
        $service = new AccountInfoService();

        $account = $service->handle();

        $this->view->setVar('account', $account);
    }

    /**
     * @Get("/courses", name="web.my.courses")
     */
    public function coursesAction()
    {
        $service = new MyCourseListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/favorites", name="web.my.favorites")
     */
    public function favoritesAction()
    {
        $service = new MyFavoriteListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/consults", name="web.my.consults")
     */
    public function consultsAction()
    {
        $service = new MyConsultListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/reviews", name="web.my.reviews")
     */
    public function reviewsAction()
    {
        $service = new MyReviewListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/orders", name="web.my.orders")
     */
    public function ordersAction()
    {
        $service = new MyOrderListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/refunds", name="web.my.refunds")
     */
    public function refundsAction()
    {
        $service = new MyRefundListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/im/friends", name="web.my.im_friends")
     */
    public function imFriendsAction()
    {
        $service = new MyFriendListService();

        $pager = $service->handle();

        $pager->items = kg_array_object($pager->items);

        $this->view->pick('my/im_friends');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/im/groups", name="web.my.im_groups")
     */
    public function imGroupsAction()
    {
        $type = $this->request->getQuery('type', 'trim', 'joined');

        $service = new MyGroupListService();

        $pager = $service->handle($type);

        $pager->items = kg_array_object($pager->items);

        $this->view->pick('my/im_groups');
        $this->view->setVar('type', $type);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/profile/update", name="web.my.update_profile")
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
