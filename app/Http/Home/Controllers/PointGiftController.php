<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Services\Logic\Point\GiftInfo as GiftInfoService;
use App\Services\Logic\Point\GiftList as GiftListService;
use App\Services\Logic\Point\GiftRedeem as GiftRedeemService;
use App\Services\Logic\Point\HotGiftList as HotGiftListService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;
use App\Services\Logic\User\Console\BalanceInfo as BalanceInfoService;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/point/gift")
 */
class PointGiftController extends Controller
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

    /**
     * @Get("/list", name="home.point_gift.list")
     */
    public function listAction()
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getPointGiftListUrl();
            return $this->response->redirect($location);
        }

        $this->seo->prependTitle('积分商城');

        $this->view->pick('point/gift/list');
    }

    /**
     * @Get("/pager", name="home.point_gift.pager")
     */
    public function pagerAction()
    {
        $service = new GiftListService();

        $pager = $service->handle();

        $pager->target = 'gift-list';

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->pick('point/gift/pager');
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.point_gift.show")
     */
    public function showAction($id)
    {
        $service = new GiftInfoService();

        $gift = $service->handle($id);

        if ($gift['deleted'] == 1) {
            $this->notFound();
        }

        if ($gift['published'] == 0) {
            $this->notFound();
        }

        $hotGifts = $this->getHotGifts();
        $userBalance = $this->getUserBalance();

        $this->seo->prependTitle(['积分商城', $gift['name']]);

        $this->view->pick('point/gift/show');
        $this->view->setVar('gift', $gift);
        $this->view->setVar('hot_gifts', $hotGifts);
        $this->view->setVar('user_balance', $userBalance);
    }

    /**
     * @Post("/{id:[0-9]+}/redeem", name="home.point_gift.redeem")
     */
    public function redeemAction($id)
    {
        $service = new GiftRedeemService();

        $service->handle($id);

        return $this->jsonSuccess(['msg' => '兑换成功']);
    }

    protected function getHotGifts()
    {
        $service = new HotGiftListService();

        return $service->handle();
    }

    protected function getUserBalance()
    {
        $service = new BalanceInfoService();

        return $service->handle();
    }

}
