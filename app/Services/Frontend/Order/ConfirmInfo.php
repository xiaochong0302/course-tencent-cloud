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

class OrderConfirm extends Service
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

            $result['item_info']['course'] = $this->handleCourse($course);
            $result['amount'] = $user->vip ? $course->vip_price : $course->market_price;

        } elseif ($itemType == OrderModel::ITEM_PACKAGE) {

            $package = $validator->checkPackage($itemId);

            $result['item_info']['package'] = $this->handlePackage($package);
            $result['amount'] = $user->vip ? $package->vip_price : $package->market_price;

        } elseif ($itemType == OrderModel::ITEM_VIP) {

            $vip = $validator->checkVip($itemId);

            $result['item_info']['vip'] = $this->handleVip($vip);
            $result['amount'] = $vip->price;

        } elseif ($itemType == OrderModel::ITEM_REWARD) {

            list($courseId, $rewardId) = explode('-', $itemId);

            $course = $validator->checkCourse($courseId);
            $reward = $validator->checkReward($rewardId);

            $result['item_info']['course'] = $this->handleCourse($course);
            $result['item_info']['reward'] = $this->handleReward($reward);
            $result['amount'] = $reward->price;
        }

        $validator->checkAmount($result['amount']);

        return $result;
    }

    protected function handleCourse(CourseModel $course)
    {
        return $this->formatCourse($course);
    }

    protected function handlePackage(PackageModel $package)
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
            $result['courses'][] = $this->formatCourse($course);
        }

        return $result;
    }

    protected function handleVip(VipModel $vip)
    {
        return [
            'id' => $vip->id,
            'title' => $vip->title,
            'expiry' => $vip->expiry,
            'price' => $vip->price,
        ];
    }

    protected function handleReward(RewardModel $reward)
    {
        return [
            'id' => $reward->id,
            'title' => $reward->title,
            'price' => $reward->price,
        ];
    }

    protected function formatCourse(CourseModel $course)
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
