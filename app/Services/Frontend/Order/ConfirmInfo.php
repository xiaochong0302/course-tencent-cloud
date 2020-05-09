<?php

namespace App\Services\Frontend\Order;

use App\Models\Course as CourseModel;
use App\Models\Order as OrderModel;
use App\Models\Package as PackageModel;
use App\Models\Reward as RewardModel;
use App\Models\Vip as VipModel;
use App\Repos\Package as PackageRepo;
use App\Services\Frontend\Service;
use App\Validators\Order as OrderValidator;

class ConfirmInfo extends Service
{

    public function handle()
    {
        $itemId = $this->request->getQuery('item_id');
        $itemType = $this->request->getQuery('item_type');

        $user = $this->getLoginUser();

        $validator = new OrderValidator();

        $validator->checkItemType($itemType);

        $result = [];

        $result['item_id'] = $itemId;
        $result['item_type'] = $itemType;

        if ($itemType == OrderModel::ITEM_COURSE) {

            $course = $validator->checkCourse($itemId);
            $courseInfo = $this->handleCourseInfo($course);

            $result['item_info']['course'] = $courseInfo;
            $result['amount'] = $user->vip ? $course->vip_price : $course->market_price;

        } elseif ($itemType == OrderModel::ITEM_PACKAGE) {

            $package = $validator->checkPackage($itemId);
            $packageInfo = $this->handlePackageInfo($package);

            $result['item_info']['package'] = $packageInfo;
            $result['amount'] = $user->vip ? $package->vip_price : $package->market_price;

        } elseif ($itemType == OrderModel::ITEM_VIP) {

            $vip = $validator->checkVip($itemId);
            $vipInfo = $this->handleVipInfo($vip);

            $result['item_info']['vip'] = $vipInfo;
            $result['amount'] = $vip->price;

        } elseif ($itemType == OrderModel::ITEM_REWARD) {

            list($courseId, $rewardId) = explode('-', $itemId);

            $course = $validator->checkCourse($courseId);
            $reward = $validator->checkReward($rewardId);

            $courseInfo = $this->handleCourseInfo($course);
            $rewardInfo = $this->handleRewardInfo($reward);

            $result['item_info']['course'] = $courseInfo;
            $result['item_info']['reward'] = $rewardInfo;
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
        $course->cover = kg_ci_img_url($course->cover);

        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'model' => $course->model,
            'level' => $course->level,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
        ];
    }

}
