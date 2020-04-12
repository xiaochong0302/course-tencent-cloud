<?php

namespace App\Services\Frontend;

use App\Models\Course as CourseModel;
use App\Models\Order as OrderModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Package as PackageRepo;
use App\Repos\Vip as VipRepo;
use App\Validators\Order as OrderValidator;

class OrderConfirm extends Service
{

    public function confirmOrder()
    {
        $query = $this->request->getQuery();

        $user = $this->getLoginUser();

        $validator = new OrderValidator();

        $validator->checkItem($query['item_id'], $query['item_type']);

        $result = [];

        $result['item_id'] = $query['item_id'];
        $result['item_type'] = $query['item_type'];

        if ($query['item_type'] == OrderModel::ITEM_COURSE) {

            $course = $this->getCourseInfo($query['item_id']);

            $result['item_info']['course'] = $course;
            $result['amount'] = $user->vip ? $course['vip_price'] : $course['market_price'];

        } elseif ($query['item_type'] == OrderModel::ITEM_PACKAGE) {

            $package = $this->getPackageInfo($query['item_id']);

            $result['item_info']['package'] = $package;
            $result['amount'] = $user->vip ? $package['vip_price'] : $package['market_price'];

        } elseif ($query['item_type'] == OrderModel::ITEM_VIP) {

            $vip = $this->getVipInfo($query['item_id']);

            $result['item_info']['vip'] = $vip;
            $result['amount'] = $vip['price'];
        }

        $validator->checkAmount($result['amount']);

        return $result;
    }

    protected function getCourseInfo($id)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($id);

        $course->cover = kg_ci_img_url($course->cover);

        $result = [
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

        return $result;
    }

    protected function getPackageInfo($id)
    {
        $packageRepo = new PackageRepo();

        $package = $packageRepo->findById($id);

        $result = [
            'id' => $package->id,
            'title' => $package->title,
            'summary' => $package->summary,
            'market_price' => $package->market_price,
            'vip_price' => $package->vip_price,
        ];

        /**
         * @var CourseModel[] $courses
         */
        $courses = $packageRepo->findCourses($id);

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

    protected function getVipInfo($id)
    {
        $vipRepo = new VipRepo();

        $result = $vipRepo->findById($id);

        return $result;
    }

}
