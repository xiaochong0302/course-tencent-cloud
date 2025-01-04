<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\PointGift as PointGiftModel;
use App\Repos\Course as CourseRepo;
use App\Repos\PointGift as PointGiftRepo;
use App\Repos\Vip as VipRepo;
use App\Validators\PointGift as PointGiftValidator;

class PointGift extends Service
{

    public function getTypes()
    {
        return PointGiftModel::types();
    }

    public function getXmCourses()
    {
        $courseRepo = new CourseRepo();

        $where = [
            'free' => 0,
            'published' => 1,
            'deleted' => 0,
        ];

        $pager = $courseRepo->paginate($where, $sort = 'latest', 1, 10000);

        if ($pager->total_items == 0) return [];

        $result = [];

        foreach ($pager->items as $item) {
            $result[] = [
                'name' => sprintf('%s（¥%0.2f）', $item->title, $item->market_price),
                'value' => $item->id,
            ];
        }

        return $result;
    }

    public function getXmVips()
    {
        $vipRepo = new VipRepo();

        $items = $vipRepo->findAll([
            'published' => 1,
            'deleted' => 0,
        ]);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'name' => sprintf('%s（¥%0.2f）', $item->title, $item->price),
                'value' => $item->id,
            ];
        }

        return $result;
    }

    public function getPointGifts()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $giftRepo = new PointGiftRepo();

        return $giftRepo->paginate($params, $sort, $page, $limit);
    }

    public function getPointGift($id)
    {
        return $this->findOrFail($id);
    }

    public function createPointGift()
    {
        $post = $this->request->getPost();

        $validator = new PointGiftValidator();

        $post['type'] = $validator->checkType($post['type']);

        $gift = new PointGiftModel();

        switch ($post['type']) {
            case PointGiftModel::TYPE_COURSE:
                $gift = $this->createCoursePointGift($post);
                break;
            case PointGiftModel::TYPE_VIP:
                $gift = $this->createVipPointGift($post);
                break;
            case PointGiftModel::TYPE_GOODS:
                $gift = $this->createGoodsPointGift($post);
                break;
        }

        return $gift;
    }

    public function updatePointGift($id)
    {
        $gift = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new PointGiftValidator();

        $data = [];

        if (isset($post['cover'])) {
            $data['cover'] = $validator->checkCover($post['cover']);
        }

        if (isset($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
        }

        if (isset($post['details'])) {
            $data['details'] = $validator->checkDetails($post['details']);
        }

        if (isset($post['attrs'])) {
            $data['attrs'] = $validator->checkAttrs($gift, $post['attrs']);
        }

        if (isset($post['point'])) {
            $data['point'] = $validator->checkPoint($post['point']);
        }

        if (isset($post['stock'])) {
            $data['stock'] = $validator->checkStock($post['stock']);
        }

        if (isset($post['redeem_limit'])) {
            $data['redeem_limit'] = $validator->checkRedeemLimit($post['redeem_limit']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $gift->update($data);

        return $gift;
    }

    public function deletePointGift($id)
    {
        $gift = $this->findOrFail($id);

        $gift->deleted = 1;

        $gift->update();

        return $gift;
    }

    public function restorePointGift($id)
    {
        $gift = $this->findOrFail($id);

        $gift->deleted = 0;

        $gift->update();

        return $gift;
    }

    protected function findOrFail($id)
    {
        $validator = new PointGiftValidator();

        return $validator->checkPointGift($id);
    }

    protected function createCoursePointGift($post)
    {
        $validator = new PointGiftValidator();

        $course = $validator->checkCourse($post['xm_course_id']);

        $giftRepo = new PointGiftRepo();

        $gift = $giftRepo->findItemGift($course->id, PointGiftModel::TYPE_COURSE);

        if ($gift) return $gift;

        $gift = new PointGiftModel();

        $gift->type = PointGiftModel::TYPE_COURSE;
        $gift->name = $course->title;
        $gift->cover = $course->cover;
        $gift->attrs = [
            'id' => $course->id,
            'title' => $course->title,
            'price' => $course->market_price,
        ];

        $gift->create();

        return $gift;
    }

    protected function createVipPointGift($post)
    {
        $validator = new PointGiftValidator();

        $vip = $validator->checkVip($post['xm_vip_id']);

        $giftRepo = new PointGiftRepo();

        $gift = $giftRepo->findItemGift($vip->id, PointGiftModel::TYPE_VIP);

        if ($gift) return $gift;

        $gift = new PointGiftModel();

        $gift->type = PointGiftModel::TYPE_VIP;
        $gift->name = sprintf('会员服务（%s个月）', $vip->expiry);
        $gift->cover = $vip->cover;
        $gift->attrs = [
            'id' => $vip->id,
            'title' => $vip->title,
            'price' => $vip->price,
        ];

        $gift->create();

        return $gift;
    }

    protected function createGoodsPointGift($post)
    {
        $validator = new PointGiftValidator();

        $gift = new PointGiftModel();

        $gift->type = PointGiftModel::TYPE_GOODS;
        $gift->name = $validator->checkName($post['name']);

        $gift->create();

        return $gift;
    }

}
