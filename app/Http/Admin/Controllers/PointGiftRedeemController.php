<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\PointGiftRedeem as PointGiftRedeemService;

/**
 * @RoutePrefix("/admin/point/gift/redeem")
 */
class PointGiftRedeemController extends Controller
{

    /**
     * @Get("/search", name="admin.point_gift_redeem.search")
     */
    public function searchAction()
    {

    }

    /**
     * @Get("/list", name="admin.point_gift_redeem.list")
     */
    public function listAction()
    {
        $redeemService = new PointGiftRedeemService();

        $pager = $redeemService->getRedeems();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/{id:[0-9]+}/deliver", name="admin.point_gift_redeem.deliver")
     */
    public function deliverAction($id)
    {
        $redeemService = new PointGiftRedeemService();

        $redeemService->deliver($id);

        return $this->jsonSuccess(['msg' => '发货成功']);
    }

}
