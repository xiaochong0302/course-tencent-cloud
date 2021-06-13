<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Library\Paginator\Query as PagerQuery;
use App\Models\Course as CourseModel;
use App\Models\FlashSale as FlashSaleModel;
use App\Models\Package as PackageModel;
use App\Models\Vip as VipModel;
use App\Repos\Course as CourseRepo;
use App\Repos\FlashSale as FlashSaleRepo;
use App\Repos\Package as PackageRepo;
use App\Repos\Vip as VipRepo;
use App\Services\Logic\FlashSale\Queue as FlashSaleQueue;
use App\Validators\FlashSale as FlashSaleValidator;

class FlashSale extends Service
{

    public function getItemTypes()
    {
        return FlashSaleModel::itemTypes();
    }

    public function getXmSchedules($id)
    {
        $schedules = FlashSaleModel::schedules();

        $sale = $this->findOrFail($id);

        $result = [];

        foreach ($schedules as $schedule) {
            $result[] = [
                'name' => $schedule['name'],
                'value' => $schedule['hour'],
                'selected' => in_array($schedule['hour'], $sale->schedules),
            ];
        }

        return $result;
    }

    public function getXmCourses()
    {
        $courseRepo = new CourseRepo();

        $items = $courseRepo->findAll(['free' => 0, 'published' => 1]);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'name' => sprintf('%s（¥%0.2f）', $item->title, $item->market_price),
                'value' => $item->id,
            ];
        }

        return $result;
    }

    public function getXmPackages()
    {
        $packageRepo = new PackageRepo();

        $items = $packageRepo->findAll(['published' => 1]);

        if ($items->count() == 0) return [];

        $result = [];

        foreach ($items as $item) {
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

        $items = $vipRepo->findAll();

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

    public function getFlashSales()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $saleRepo = new FlashSaleRepo();

        return $saleRepo->paginate($params, $sort, $page, $limit);
    }

    public function getFlashSale($id)
    {
        return $this->findOrFail($id);
    }

    public function createFlashSale()
    {
        $post = $this->request->getPost();

        $validator = new FlashSaleValidator();

        $post['item_type'] = $validator->checkItemType($post['item_type']);

        $sale = new FlashSaleModel();

        switch ($post['item_type']) {
            case FlashSaleModel::ITEM_COURSE:
                $sale = $this->createCourseFlashSale($post);
                break;
            case FlashSaleModel::ITEM_PACKAGE:
                $sale = $this->createPackageFlashSale($post);
                break;
            case FlashSaleModel::ITEM_VIP:
                $sale = $this->createVipFlashSale($post);
                break;
        }

        return $sale;
    }

    public function updateFlashSale($id)
    {
        $sale = $this->findOrFail($id);

        $post = $this->request->getPost();

        $originInfo = $this->getOriginInfo($sale->item_id, $sale->item_type);

        $validator = new FlashSaleValidator();

        $data = [];

        $data['item_info'] = $originInfo['item_info'];

        if (isset($post['start_time']) && isset($post['end_time'])) {
            $data['start_time'] = $validator->checkStartTime($post['start_time']);
            $data['end_time'] = $validator->checkEndTime($post['end_time']);
            $validator->checkTimeRange($data['start_time'], $data['end_time']);
        }

        if (isset($post['xm_schedules'])) {
            $data['schedules'] = $validator->checkSchedules($post['xm_schedules']);
        }

        if (isset($post['stock'])) {
            $data['stock'] = $validator->checkStock($post['stock']);
        }

        if (isset($post['price'])) {
            $data['price'] = $validator->checkPrice($originInfo['item_price'], $post['price']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $sale->update($data);

        $this->initFlashSaleQueue($sale->id);

        return $sale;
    }

    public function deleteFlashSale($id)
    {
        $sale = $this->findOrFail($id);

        $sale->deleted = 1;

        $sale->update();

        return $sale;
    }

    public function restoreFlashSale($id)
    {
        $sale = $this->findOrFail($id);

        $sale->deleted = 0;

        $sale->update();

        return $sale;
    }

    protected function createCourseFlashSale($post)
    {
        $validator = new FlashSaleValidator();

        $course = $validator->checkCourse($post['xm_course_id']);

        $originInfo = $this->getOriginInfo($course->id, FlashSaleModel::ITEM_COURSE);

        $sale = new FlashSaleModel();

        $sale->item_id = $course->id;
        $sale->item_type = FlashSaleModel::ITEM_COURSE;
        $sale->item_info = $originInfo['item_info'];

        $sale->create();

        return $sale;
    }

    protected function createPackageFlashSale($post)
    {
        $validator = new FlashSaleValidator();

        $package = $validator->checkPackage($post['xm_package_id']);

        $originInfo = $this->getOriginInfo($package->id, FlashSaleModel::ITEM_PACKAGE);

        $sale = new FlashSaleModel();

        $sale->item_id = $package->id;
        $sale->item_type = FlashSaleModel::ITEM_PACKAGE;
        $sale->item_info = $originInfo['item_info'];

        $sale->create();

        return $sale;
    }

    protected function createVipFlashSale($post)
    {
        $validator = new FlashSaleValidator();

        $vip = $validator->checkVip($post['xm_vip_id']);

        $originInfo = $this->getOriginInfo($vip->id, FlashSaleModel::ITEM_VIP);

        $sale = new FlashSaleModel();

        $sale->item_id = $vip->id;
        $sale->item_type = FlashSaleModel::ITEM_VIP;
        $sale->item_info = $originInfo['item_info'];

        $sale->create();

        return $sale;
    }

    protected function getOriginInfo($itemId, $itemType)
    {
        $result = [
            'item_info' => [],
            'item_price' => 0.00,
        ];

        if ($itemType == FlashSaleModel::ITEM_COURSE) {

            $courseRepo = new CourseRepo();

            $course = $courseRepo->findById($itemId);

            $result = [
                'item_info' => [
                    'course' => [
                        'id' => $course->id,
                        'title' => $course->title,
                        'cover' => CourseModel::getCoverPath($course->cover),
                        'market_price' => $course->market_price,
                    ],
                ],
                'item_price' => $course->market_price,
            ];

        } elseif ($itemType == FlashSaleModel::ITEM_PACKAGE) {

            $packageRepo = new PackageRepo();

            $package = $packageRepo->findById($itemId);

            $result = [
                'item_info' => [
                    'package' => [
                        'id' => $package->id,
                        'title' => $package->title,
                        'cover' => PackageModel::getCoverPath($package->cover),
                        'market_price' => $package->market_price,
                    ],
                ],
                'item_price' => $package->market_price,
            ];

        } elseif ($itemType == FlashSaleModel::ITEM_VIP) {

            $vipRepo = new VipRepo();

            $vip = $vipRepo->findById($itemId);

            $result = [
                'item_info' => [
                    'vip' => [
                        'id' => $vip->id,
                        'title' => $vip->title,
                        'cover' => VipModel::getCoverPath($vip->cover),
                        'expiry' => $vip->expiry,
                        'price' => $vip->price,
                    ],
                ],
                'item_price' => $vip->price,
            ];
        }

        return $result;
    }

    protected function initFlashSaleQueue($id)
    {
        $queue = new FlashSaleQueue();

        $queue->init($id);
    }

    protected function findOrFail($id)
    {
        $validator = new FlashSaleValidator();

        return $validator->checkFlashSale($id);
    }

}
