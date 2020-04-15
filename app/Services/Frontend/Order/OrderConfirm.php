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

    public function confirmOrder()
    {
        $query = $this->request->getQuery();

        $user = $this->getLoginUser();

        $validator = new OrderValidator();

        $validator->checkItemType($query['item_type']);

        $result = [];

        $result['item_id'] = $query['item_id'];
        $result['item_type'] = $query['item_type'];

        if ($query['item_type'] == OrderModel::ITEM_COURSE) {

            $course = $validator->checkCourseItem($query['item_id']);
            $courseInfo = $this->handleCourseInfo($course);

            $result['item_info']['course'] = $courseInfo;
            $result['amount'] = $user->vip ? $course->vip_price : $course->market_price;

        } elseif ($query['item_type'] == OrderModel::ITEM_PACKAGE) {

            $package = $validator->checkPackageItem($query['item_id']);
            $packageInfo = $this->handlePackageInfo($package);

            $result['item_info']['package'] = $packageInfo;
            $result['amount'] = $user->vip ? $package->vip_price : $package->market_price;

        } elseif ($query['item_type'] == OrderModel::ITEM_VIP) {

            $vip = $validator->checkVipItem($query['item_id']);
            $vipInfo = $this->handleVipInfo($vip);

            $result['item_info']['vip'] = $vipInfo;
            $result['amount'] = $vip->price;

        } elseif ($query['item_type'] == OrderModel::ITEM_REWARD) {

            list($courseId, $rewardId) = explode('-', $query['item_id']);

            $course = $validator->checkCourseItem($courseId);
            $reward = $validator->checkRewardItem($rewardId);

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
        $course->cover = kg_ci_img_url($course->cover);

        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'summary' => $course->summary,
            'model' => $course->model,
            'level' => $course->level,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
        ];
    }

    protected function handlePackageInfo(PackageModel $package)
    {
        $result = [
            'id' => $package->id,
            'title' => $package->title,
            'summary' => $package->summary,
            'market_price' => $package->market_price,
            'vip_price' => $package->vip_price,
        ];

        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($package->id);

        $baseUrl = kg_ci_base_url();

        foreach ($courses as $course) {

            $course->cover = $baseUrl . $course->cover;

            $result['courses'][] = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'summary' => $course->summary,
                'model' => $course->model,
                'level' => $course->level,
                'study_expiry' => $course->study_expiry,
                'refund_expiry' => $course->refund_expiry,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
            ];
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

}
