<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Vip as VipModel;
use App\Repos\Vip as VipRepo;
use App\Validators\Vip as VipValidator;
use Phalcon\Paginator\RepositoryInterface as PagerRepoInterface;

class Vip extends Service
{

    public function getVips(): PagerRepoInterface
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $vipRepo = new VipRepo();

        return $vipRepo->paginate($params, $sort, $page, $limit);
    }

    public function createVip(): VipModel
    {
        $post = $this->request->getPost();

        $validator = new VipValidator();

        $data = [];

        $data['expiry'] = $validator->checkExpiry($post['expiry']);
        $data['price'] = $validator->checkPrice($post['price']);
        $data['title'] = sprintf('%s个月', $data['expiry']);

        $vip = new VipModel();

        $vip->assign($data);

        $vip->create();

        return $vip;
    }

    public function getVip(int $id): VipModel
    {
        return $this->findOrFail($id);
    }

    public function updateVip(int $id): VipModel
    {
        $vip = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new VipValidator();

        $data = [];

        if (isset($post['cover'])) {
            $data['cover'] = $validator->checkCover($post['cover']);
        }

        if (isset($post['expiry'])) {
            $data['expiry'] = $validator->checkExpiry($post['expiry']);
            $data['title'] = sprintf('%s个月', $data['expiry']);
        }

        if (isset($post['price'])) {
            $data['price'] = $validator->checkPrice($post['price']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $vip->assign($data);

        $vip->update();

        $this->syncSaleInfo($vip);

        return $vip;
    }

    public function deleteVip(int $id): VipModel
    {
        $vip = $this->findOrFail($id);

        $vip->deleted = 1;

        $vip->update();

        return $vip;
    }

    public function restoreVip(int $id): VipModel
    {
        $vip = $this->findOrFail($id);

        $vip->deleted = 0;

        $vip->update();

        return $vip;
    }

    protected function syncSaleInfo(VipModel $vip): void
    {
        if ($vip->hasUpdated(['title', 'cover', 'price'])) {
            $sync = new FlashSaleSync();
            $sync->syncVipInfo($vip);

            $sync = new PointGiftSync();
            $sync->syncVipInfo($vip);

            $sync = new GrouponSync();
            $sync->syncVipInfo($vip);

            $sync = new CouponSync();
            $sync->syncVipInfo($vip);

            $sync = new DistributionSync();
            $sync->syncVipInfo($vip);
        }
    }

    protected function findOrFail(int $id): VipModel
    {
        $validator = new VipValidator();

        return $validator->checkVip($id);
    }

}
