<?php

namespace App\Http\Home\Controllers;

use App\Services\Logic\Point\GiftInfo as GiftInfoService;
use App\Services\Logic\Point\GiftList as GiftListService;
use App\Services\Logic\Point\HotGiftList as HotGiftListService;
use App\Services\Logic\Point\PointRedeem as GiftRedeemService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/point/gift")
 */
class PointGiftController extends Controller
{

    /**
     * @Get("/list", name="home.point_gift.list")
     */
    public function listAction()
    {
        $this->seo->prependTitle('积分兑换');

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

        $hotGifts = $this->getHotGifts();

        $this->seo->prependTitle(['积分兑换', $gift['name']]);

        $this->view->pick('point/gift/show');
        $this->view->setVar('gift', $gift);
        $this->view->setVar('hot_gifts', $hotGifts);
    }

    /**
     * @Post("/redeem", name="home.point_gift.redeem")
     */
    public function redeemAction()
    {
        $service = new GiftRedeemService();

        $service->handle();

        return $this->jsonSuccess(['msg' => '兑换成功']);
    }

    protected function getHotGifts()
    {
        $service = new HotGiftListService();

        return $service->handle();
    }

}
