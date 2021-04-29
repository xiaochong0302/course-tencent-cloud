<?php

namespace App\Http\Home\Controllers;

use App\Repos\WeChatSubscribe as WeChatSubscribeRepo;
use App\Services\Logic\Account\OAuthProvider as OAuthProviderService;
use App\Services\Logic\User\Console\AccountInfo as AccountInfoService;
use App\Services\Logic\User\Console\ArticleList as ArticleListService;
use App\Services\Logic\User\Console\ConnectDelete as ConnectDeleteService;
use App\Services\Logic\User\Console\ConnectList as ConnectListService;
use App\Services\Logic\User\Console\ConsultList as ConsultListService;
use App\Services\Logic\User\Console\ContactInfo as ContactInfoService;
use App\Services\Logic\User\Console\ContactUpdate as ContactUpdateService;
use App\Services\Logic\User\Console\CourseList as CourseListService;
use App\Services\Logic\User\Console\FavoriteList as FavoriteListService;
use App\Services\Logic\User\Console\FriendList as FriendListService;
use App\Services\Logic\User\Console\GroupList as GroupListService;
use App\Services\Logic\User\Console\NotificationList as NotificationListService;
use App\Services\Logic\User\Console\NotificationRead as NotificationReadService;
use App\Services\Logic\User\Console\NotifyStats as NotifyStatsService;
use App\Services\Logic\User\Console\Online as OnlineService;
use App\Services\Logic\User\Console\OrderList as OrderListService;
use App\Services\Logic\User\Console\PointHistory as PointHistoryService;
use App\Services\Logic\User\Console\PointRedeemList as PointRedeemListService;
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

    public function initialize()
    {
        parent::initialize();

        $authUser = $this->getAuthUser(false);

        $this->seo->prependTitle('用户中心');

        $this->view->setVar('auth_user', $authUser);
    }

    /**
     * @Get("/", name="home.uc.index")
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
     * @Get("/contact", name="home.uc.contact")
     */
    public function contactAction()
    {
        $service = new ContactInfoService();

        $contact = $service->handle();

        $this->view->pick('user/console/contact');
        $this->view->setVar('contact', $contact);
    }

    /**
     * @Get("/account", name="home.uc.account")
     */
    public function accountAction()
    {
        $type = $this->request->getQuery('type', 'string', 'info');

        $service = new AccountInfoService();

        $captcha = $service->getSettings('captcha');

        $account = $service->handle();

        $service = new OAuthProviderService();

        $oauthProvider = $service->handle();

        $service = new ConnectListService();

        $connects = $service->handle();

        if ($type == 'info') {
            $this->view->pick('user/console/account_info');
        } elseif ($type == 'phone') {
            $this->view->pick('user/console/account_phone');
        } elseif ($type == 'email') {
            $this->view->pick('user/console/account_email');
        } elseif ($type == 'password') {
            $this->view->pick('user/console/account_password');
        }

        $this->view->setVar('oauth_provider', $oauthProvider);
        $this->view->setVar('connects', $connects);
        $this->view->setVar('captcha', $captcha);
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
     * @Get("/articles", name="home.uc.articles")
     */
    public function articlesAction()
    {
        $service = new ArticleListService();

        $pager = $service->handle();

        $this->view->pick('user/console/articles');
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
     * @Get("/point/history", name="home.uc.point_history")
     */
    public function pointHistoryAction()
    {
        $service = new PointHistoryService();

        $pager = $service->handle();

        $this->view->pick('user/console/point_history');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/point/redeems", name="home.uc.point_redeems")
     */
    public function pointRedeemsAction()
    {
        $service = new PointRedeemListService();

        $pager = $service->handle();

        $this->view->pick('user/console/point_redeems');
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
     * @Get("/notifications", name="home.uc.notifications")
     */
    public function notificationsAction()
    {
        $service = new NotificationListService();

        $pager = $service->handle();

        $service = new NotificationReadService();

        $service->handle();

        $this->view->pick('user/console/notifications');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/subscribe", name="home.uc.subscribe")
     */
    public function subscribeAction()
    {
        $subscribeRepo = new WeChatSubscribeRepo();

        $subscribe = $subscribeRepo->findByUserId($this->authUser->id);

        $subscribed = $subscribe ? 1 : 0;

        $this->view->pick('user/console/subscribe');
        $this->view->setVar('subscribed', $subscribed);
    }

    /**
     * @Get("/notify/stats", name="home.uc.notify_stats")
     */
    public function notifyStatsAction()
    {
        $service = new NotifyStatsService();

        $stats = $service->handle();

        return $this->jsonSuccess(['stats' => $stats]);
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
     * @Post("/contact/update", name="home.uc.update_contact")
     */
    public function updateContactAction()
    {
        $service = new ContactUpdateService();

        $service->handle();

        $content = ['msg' => '更新收货信息成功'];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/connect/{id:[0-9]+}/delete", name="home.uc.unconnect")
     */
    public function deleteConnectAction($id)
    {
        $service = new ConnectDeleteService();

        $service->handle($id);

        $location = $this->url->get(['for' => 'home.uc.account']);

        $content = [
            'location' => $location,
            'msg' => '解除登录绑定成功',
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
