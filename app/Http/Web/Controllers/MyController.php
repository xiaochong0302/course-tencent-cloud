<?php

namespace App\Http\Web\Controllers;

use App\Services\Frontend\My\AccountInfo as AccountInfoService;
use App\Services\Frontend\My\ConsultList as MyConsultListService;
use App\Services\Frontend\My\CourseList as MyCourseListService;
use App\Services\Frontend\My\ImFriendDelete as MyFriendDeleteService;
use App\Services\Frontend\My\ImGroupDelete as MyGroupDeleteService;
use App\Services\Frontend\My\OrderList as MyOrderListService;
use App\Services\Frontend\My\RefundList as MyRefundListService;
use App\Services\Frontend\My\ReviewList as MyReviewListService;
use App\Services\Frontend\My\UserInfo as UserInfoService;
use App\Services\Frontend\My\UserUpdate as UserUpdateService;
use App\Services\Frontend\User\FriendList as UserFriendListService;
use App\Services\Frontend\User\GroupList as UserGroupListService;

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
     * @Get("/home", name="web.my.home")
     */
    public function homeAction()
    {
        $this->response->redirect([
            'for' => 'web.user.show',
            'id' => $this->authUser->id,
        ]);
    }

    /**
     * @Get("/profile", name="web.my.profile")
     */
    public function profileAction()
    {
        $service = new UserInfoService();

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
        $service = new MyConsultListService();

        $pager = $service->handle();

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
     * @Get("/friends", name="web.my.friends")
     */
    public function friendsAction()
    {
        $service = new UserFriendListService();

        $pager = $service->handle($this->authUser->id);

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/groups", name="web.my.groups")
     */
    public function groupsAction()
    {
        $service = new UserGroupListService();

        $pager = $service->handle($this->authUser->id);

        $pager->items = kg_array_object($pager->items);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/profile/update", name="web.my.update_profile")
     */
    public function updateProfileAction()
    {
        $service = new UserUpdateService();

        $service->handle();

        $content = ['msg' => '更新资料成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/friend/{id:[0-9]+}/delete", name="web.my.delete_friend")
     */
    public function deleteFriendAction($id)
    {
        $service = new MyFriendDeleteService();

        $service->handle($id);

        $content = ['msg' => '删除好友成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/group/{id:[0-9]+}/delete", name="web.my.delete_group")
     */
    public function deleteGroupAction($id)
    {
        $service = new MyGroupDeleteService();

        $service->handle($id);

        $content = ['msg' => '退出群组成功'];

        return $this->jsonSuccess($content);
    }

}
