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

class Vip extends Service
{

    public function getVips()
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

    public function getVip($id)
    {
        return $this->findOrFail($id);
    }

    public function createVip()
    {
        $post = $this->request->getPost();

        $validator = new VipValidator();

        $data = [];

        $data['expiry'] = $validator->checkExpiry($post['expiry']);
        $data['price'] = $validator->checkPrice($post['price']);
        $data['title'] = sprintf('%s个月', $data['expiry']);

        $vip = new VipModel();

        $vip->create($data);

        return $vip;
    }

    public function updateVip($id)
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

        $vip->update($data);

        return $vip;
    }

    public function deleteVip($id)
    {
        $vip = $this->findOrFail($id);

        $vip->deleted = 1;

        $vip->update();

        return $vip;
    }

    public function restoreVip($id)
    {
        $vip = $this->findOrFail($id);

        $vip->deleted = 0;

        $vip->update();

        return $vip;
    }

    protected function findOrFail($id)
    {
        $validator = new VipValidator();

        return $validator->checkVip($id);
    }

}
