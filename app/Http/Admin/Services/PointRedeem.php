<?php

namespace App\Http\Admin\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\PointGift as PointGiftModel;
use App\Models\PointRedeem as PointRedeemModel;
use App\Repos\PointRedeem as PointRedeemRepo;
use App\Validators\PointRedeem as PointRedeemValidator;

class PointRedeem extends Service
{

    public function getRedeems()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $redeemRepo = new PointRedeemRepo();

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

        $redeem->status = PointRedeemModel::STATUS_FINISHED;

        $redeem->update();

        return $redeem;
    }

    protected function findOrFail($id)
    {
        $validator = new PointRedeemValidator();

        return $validator->checkRedeem($id);
    }

}
