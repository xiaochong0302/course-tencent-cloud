<?php

namespace App\Http\Home\Controllers;

use App\Services\Logic\Point\PointRedeem as PointRedeemService;

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
        $service = new PointRedeemService();

        $service->handle();

        return $this->jsonSuccess(['msg' => '兑换请求提交成功']);
    }

}
