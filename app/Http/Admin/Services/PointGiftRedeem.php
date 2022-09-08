<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\PointGift as PointGiftModel;
use App\Models\PointGiftRedeem as PointGiftRedeemModel;
use App\Repos\PointGiftRedeem as PointGiftRedeemRepo;
use App\Services\Logic\Notice\External\PointGoodsDeliver as PointGoodsDeliverNotice;
use App\Validators\PointGiftRedeem as PointGiftRedeemValidator;

class PointGiftRedeem extends Service
{

    public function getRedeems()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $redeemRepo = new PointGiftRedeemRepo();

        return $redeemRepo->paginate($params, $sort, $page, $limit);
    }

    public function getRedeem($id)
    {
        return $this->findOrFail($id);
    }

    public function deliver($id)
    {
        $redeem = $this->findOrFail($id);

        if ($redeem->gift_type != PointGiftModel::TYPE_GOODS) {
            return $redeem;
        }

        $redeem->status = PointGiftRedeemModel::STATUS_FINISHED;

        $redeem->update();

        $this->handleGoodsDeliverNotice($redeem);

        return $redeem;
    }

    protected function handleGoodsDeliverNotice(PointGiftRedeemModel $redeem)
    {
        $notice = new PointGoodsDeliverNotice();

        $notice->createTask($redeem);
    }

    protected function findOrFail($id)
    {
        $validator = new PointGiftRedeemValidator();

        return $validator->checkRedeem($id);
    }

}
