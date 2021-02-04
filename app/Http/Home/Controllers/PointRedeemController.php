<?php

namespace App\Http\Home\Controllers;

use App\Services\Logic\Point\PointRedeem as GiftRedeemService;

/**
 * @RoutePrefix("/point/redeem")
 */
class PointRedeemController extends Controller
{

    /**
     * @Post("/create", name="home.point_redeem.create")
     */
    public function createAction()
    {
        $service = new GiftRedeemService();

        $service->handle();

        return $this->jsonSuccess(['msg' => '兑换成功']);
    }

    /**
     * @Get("/list", name="home.point_gift.list")
     */
    public function listAction()
    {
        $this->seo->prependTitle('积分兑换');

        $this->view->pick('point/gift/list');
    }

}
