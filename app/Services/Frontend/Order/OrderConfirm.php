<?php

namespace App\Services\Frontend\Order;

use App\Models\Course as CourseModel;
use App\Models\Order as OrderModel;
use App\Models\Package as PackageModel;
use App\Models\Reward as RewardModel;
use App\Models\Vip as VipModel;
use App\Repos\Package as PackageRepo;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Order as OrderValidator;

class OrderConfirm extends FrontendService
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
            $result['amount'] = $user->vip ? $course->vip_price : $course->market_price;

        } elseif ($itemType == OrderModel::ITEM_PACKAGE) {

            $package = $validator->checkPackage($itemId);

            $result['item_info']['package'] = $this->handlePackageInfo($package);
            $result['amount'] = $user->vip ? $package->vip_price : $package->market_price;

        } elseif ($itemType == OrderModel::ITEM_VIP) {

            $vip = $validator->checkVip($itemId);

            $result['item_info']['vip'] = $this->handleVipInfo($vip);
            $result['amount'] = $vip->price;

        } elseif ($itemType == OrderModel::ITEM_REWARD) {

            list($courseId, $rewardId) = explode('-', $itemId);

            $course = $validator->checkCourse($courseId);
            $reward = $validator->checkReward($rewardId);

            $result['item_info']['course'] = $this->handleCourseInfo($course);
            $result['item_info']['reward'] = $this->handleRewardInfo($reward);
            $result['amount'] = $reward->price;
        }

        $validator->checkAmount($result['amount']);

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
            'expiry' => $vip->expiry,
            'price' => $vip->price,
        ];
    }

    protected function handleRewardInfo(RewardModel $reward)
    {
        return [
            'id' => $reward->id,
            'title' => $reward->title,
            'price' => $reward->price,
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
            'rating' => $course->rating,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
        ];
    }

}
