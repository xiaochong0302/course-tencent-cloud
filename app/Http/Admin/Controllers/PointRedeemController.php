<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\PointRedeem as PointRedeemService;

/**
 * @RoutePrefix("/admin/point/redeem")
 */
class PointRedeemController extends Controller
{

    /**
     * @Get("/search", name="admin.point_redeem.search")
     */
    public function searchAction()
    {
        $this->view->pick('point/redeem/search');
    }

    /**
     * @Get("/list", name="admin.point_redeem.list")
     */
    public function listAction()
    {
        $redeemService = new PointRedeemService();

        $pager = $redeemService->getRedeems();

        $this->view->pick('point/redeem/list');

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Post("/{id:[0-9]+}/deliver", name="admin.point_redeem.deliver")
     */
    public function deliverAction($id)
    {
        $redeemService = new PointRedeemService();

        $redeemService->deliver($id);

        return $this->jsonSuccess(['msg' => '发货成功']);
    }

}
