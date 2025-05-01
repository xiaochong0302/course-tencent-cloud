<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Order;

use App\Models\Course as CourseModel;
use App\Models\Order as OrderModel;
use App\Models\Package as PackageModel;
use App\Models\Vip as VipModel;
use App\Repos\Package as PackageRepo;
use App\Services\Logic\Service as LogicService;
use App\Validators\Order as OrderValidator;

class OrderConfirm extends LogicService
{

    public function handle($itemId, $itemType)
    {
        $user = $this->getLoginUser();

        $validator = new OrderValidator();

        $validator->checkItemType($itemType);

        $result = [];

        $result['item_id'] = $itemId;
        $result['item_type'] = $itemType;

        if ($itemType == OrderModel::ITEM_COURSE) {

            $course = $validator->checkCourse($itemId);

            $result['item_info']['course'] = $this->handleCourseInfo($course);

            $result['total_amount'] = $course->market_price;
            $result['pay_amount'] = $user->vip ? $course->vip_price : $course->market_price;
            $result['discount_amount'] = $result['total_amount'] - $result['pay_amount'];

        } elseif ($itemType == OrderModel::ITEM_PACKAGE) {

            $package = $validator->checkPackage($itemId);

            $result['item_info']['package'] = $this->handlePackageInfo($package);

            $result['total_amount'] = 0;

            foreach ($result['item_info']['package']['courses'] as $course) {
                $result['total_amount'] += $course['market_price'];
            }

            $result['pay_amount'] = $user->vip ? $package->vip_price : $package->market_price;
            $result['discount_amount'] = $result['total_amount'] - $result['pay_amount'];

        } elseif ($itemType == OrderModel::ITEM_VIP) {

            $vip = $validator->checkVip($itemId);

            $result['item_info']['vip'] = $this->handleVipInfo($vip);

            $result['total_amount'] = $vip->price;
            $result['pay_amount'] = $vip->price;
            $result['discount_amount'] = 0;
        }

        $validator->checkAmount($result['pay_amount']);

        return $result;
    }

    protected function handleCourseInfo(CourseModel $course)
    {
        return $this->formatCourseInfo($course);
    }

    protected function handlePackageInfo(PackageModel $package)
    {
        $result = [
            'id' => $package->id,
            'title' => $package->title,
            'market_price' => $package->market_price,
            'vip_price' => $package->vip_price,
        ];

        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($package->id);

        foreach ($courses as $course) {
            $result['courses'][] = $this->formatCourseInfo($course);
        }

        return $result;
    }

    protected function handleVipInfo(VipModel $vip)
    {
        return [
            'id' => $vip->id,
            'title' => $vip->title,
            'cover' => $vip->cover,
            'expiry' => $vip->expiry,
            'price' => $vip->price,
        ];
    }

    protected function formatCourseInfo(CourseModel $course)
    {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'model' => $course->model,
            'level' => $course->level,
            'attrs' => $course->attrs,
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
        ];
    }

}
