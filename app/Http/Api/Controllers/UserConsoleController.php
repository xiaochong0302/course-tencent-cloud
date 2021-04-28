<?php

namespace App\Http\Api\Controllers;

use App\Services\Logic\User\Console\AccountInfo as AccountInfoService;
use App\Services\Logic\User\Console\ConsultList as ConsultListService;
use App\Services\Logic\User\Console\CourseList as CourseListService;
use App\Services\Logic\User\Console\FavoriteList as FavoriteListService;
use App\Services\Logic\User\Console\FriendList as FriendListService;
use App\Services\Logic\User\Console\GroupList as GroupListService;
use App\Services\Logic\User\Console\NotificationList as NotificationListService;
use App\Services\Logic\User\Console\NotificationRead as NotificationReadService;
use App\Services\Logic\User\Console\NotifyStats as NotifyStatsService;
use App\Services\Logic\User\Console\OrderList as OrderListService;
use App\Services\Logic\User\Console\ProfileInfo as ProfileInfoService;
use App\Services\Logic\User\Console\ProfileUpdate as ProfileUpdateService;
use App\Services\Logic\User\Console\RefundList as RefundListService;
use App\Services\Logic\User\Console\ReviewList as ReviewListService;

/**
 * @RoutePrefix("/api/uc")
 */
class UserConsoleController extends Controller
{

    /**
     * @Get("/profile", name="api.uc.profile")
     */
    public function profileAction()
    {
        $service = new ProfileInfoService();

        $profile = $service->handle();

        return $this->jsonSuccess(['profile' => $profile]);
    }

    /**
     * @Get("/account", name="api.uc.account")
     */
    public function accountAction()
    {
        $service = new AccountInfoService();

        $account = $service->handle();

        return $this->jsonSuccess(['account' => $account]);
    }

    /**
     * @Get("/courses", name="api.uc.courses")
     */
    public function coursesAction()
    {
        $service = new CourseListService();

        $pager = $service->handle();

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/favorites", name="api.uc.favorites")
     */
    public function favoritesAction()
    {
        $service = new FavoriteListService();

        $pager = $service->handle();

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/consults", name="api.uc.consults")
     */
    public function consultsAction()
    {
        $service = new ConsultListService();

        $pager = $service->handle();

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/reviews", name="api.uc.reviews")
     */
    public function reviewsAction()
    {
        $service = new ReviewListService();

        $pager = $service->handle();

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/orders", name="api.uc.orders")
     */
    public function ordersAction()
    {
        $service = new OrderListService();

        $pager = $service->handle();

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/refunds", name="api.uc.refunds")
     */
    public function refundsAction()
    {
        $service = new RefundListService();

        $pager = $service->handle();

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/friends", name="api.uc.friends")
     */
    public function friendsAction()
    {
        $service = new FriendListService();

        $pager = $service->handle();

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/groups", name="api.uc.groups")
     */
    public function groupsAction()
    {
        $service = new GroupListService();

        $pager = $service->handle();

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/notifications", name="api.uc.notifications")
     */
    public function notificationsAction()
    {
        $service = new NotificationListService();

        $pager = $service->handle();

        $service = new NotificationReadService();

        $service->handle();

        return $this->jsonSuccess(['pager' => $pager]);
    }

    /**
     * @Get("/notify/stats", name="api.uc.notify_stats")
     */
    public function notifyStatsAction()
    {
        $service = new NotifyStatsService();

        $stats = $service->handle();

        return $this->jsonSuccess(['stats' => $stats]);
    }

    /**
     * @Post("/profile/update", name="api.uc.update_profile")
     */
    public function updateProfileAction()
    {
        $service = new ProfileUpdateService();

        $service->handle();

        return $this->jsonSuccess();
    }

}
