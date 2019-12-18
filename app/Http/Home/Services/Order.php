<?php

namespace App\Http\Home\Services;

use App\Models\Order as OrderModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseStudent as CourseUserRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\OrderItem as OrderItemRepo;
use App\Repos\Package as PackageRepo;
use App\Validators\Order as OrderFilter;
use Yansongda\Pay\Pay;

class Order extends Service
{

    public function getCourse($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findOrFail($courseId);

        return $course;
    }

    public function getPackage($packageId)
    {
        $packageRepo = new packageRepo();

        $package = $packageRepo->findOrFail($packageId);

        return $package;
    }

    public function getPackageCourses($packageId)
    {
        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findPackageCourses($packageId);

        return $courses;
    }

    public function getOrder($tradeNo)
    {
        $order = $this->findOrFail($tradeNo);

        return $order;
    }

    public function getOrderItems($orderId)
    {
        $orderRepo = new OrderRepo();

        $orderItems = $orderRepo->findOrderItems($orderId);

        return $orderItems;
    }

    public function create()
    {
        $user = $this->getLoggedUser();

        $post = $this->request->getPost();

        $filter = new OrderFilter();

        $filter->checkDailyLimit($user->id);

        $data = [];

        $data['user_id'] = $user->id;
        $data['item_id'] = $filter->checkItemId($post['item_id']);
        $data['item_type'] = $filter->checkItemType($post['item_type']);

        switch ($data['item_type']) {

            case OrderModel::ITEM_TYPE_COURSE:

                $course = $this->getCourse($data['item_id']);

                $data['amount'] = $filter->checkAmount($course->price);

                $data['provider_id'] = $course->user_id;

                $filter->checkIfBoughtCourse($user->id, $course->id);

                $order = $this->createCourseOrder($course, $data);

                break;

            case OrderModel::ITEM_TYPE_PACKAGE:

                $package = $this->getPackage($data['item_id']);

                $data['amount'] = $filter->checkAmount($package->price);

                $data['provider_id'] = $package->user_id;

                $filter->checkIfBoughtPackage($user->id, $package->id);

                $order = $this->createPackageOrder($package, $data);

                break;

            case OrderModel::ITEM_TYPE_REWARD:

                $course = $this->getCourse($data['item_id']);

                $data['amount'] = $filter->checkAmount($post['amount']);

                $data['provider_id'] = $course->user_id;

                $order = $this->createRewardOrder($course, $data);

                break;
        }

        return $order;
    }

    public function cancel()
    {
        $user = $this->getLoggedUser();

        $tradeNo = $this->request->getPost('trade_no');

        $order = $this->findOrFail($tradeNo);

        $filter = new OrderFilter();

        $filter->checkOwner($user->id, $order->user_id);

        $filter->checkIfAllowCancel($order);

        $order->status = OrderModel::STATUS_CLOSED;

        $order->update();
    }

    public function qrCode($tradeNo, $payChannel)
    {
        $order = $this->findOrFail($tradeNo);

        $filter = new OrderFilter();

        $filter->checkIfAllowPay($order);

        $filter->checkPayChannel($payChannel);

        switch ($payChannel) {

            case OrderModel::PAY_CHANNEL_ALIPAY:
                $qrCodeText = $this->alipayQrCode($order);
                break;

            case OrderModel::PAY_CHANNEL_WXPAY:
                $qrCodeText = $this->wxpayQrCode($order);
                break;
        }

        $order->pay_channel = $payChannel;

        $order->update();

        return urlencode($qrCodeText);
    }

    public function notify($payChannel)
    {
        switch ($payChannel) {

            case OrderModel::PAY_CHANNEL_ALIPAY:
                $this->alipayNotify();
                break;

            case OrderModel::PAY_CHANNEL_WXPAY:
                $this->wxpayNotify();
                break;
        }
    }

    private function createCourseOrder($course, $data)
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->create($data);

        $orderItemRepo = new OrderItemRepo();

        $orderItemRepo->create([
            'order_id' => $order->id,
            'item_info' => [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'price' => $course->price,
                'expiry' => $course->expiry,
            ],
        ]);

        return $order;
    }

    private function createPackageOrder($package, $data)
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->create($data);

        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findPackageCourses($package->id);

        $orderItemRepo = new OrderItemRepo();

        foreach ($courses as $course) {

            $orderItemRepo->create([
                'order_id' => $order->id,
                'item_info' => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'cover' => $course->cover,
                    'price' => $course->price,
                    'expiry' => $course->expiry,
                ],
            ]);
        }

        return $order;
    }

    private function createRewardOrder($course, $data)
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->create($data);

        $orderItemRepo = new OrderItemRepo();

        $orderItemRepo->create([
            'order_id' => $order->id,
            'item_info' => [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'price' => $course->price,
                'expiry' => $course->expiry,
            ],
        ]);

        return $order;
    }

    private function alipayQrCode($order)
    {
        $config = $this->config->alipay->toArray();

        $config['notify_url'] = kg_base_url() . $this->url->get([
                    'for' => 'home.order.notify',
                    'channel' => OrderModel::PAY_CHANNEL_ALIPAY,
        ]);

        $data = [
            'out_trade_no' => $order->trade_no,
            'total_amount' => $order->amount,
            'subject' => '',
        ];

        $pay = Pay::alipay($config)->scan($data);

        return $pay->qr_code;
    }

    private function wxpayQrCode($order)
    {
        $config = $this->config->wxpay->toArray();

        $config['notify_url'] = kg_base_url() . $this->url->get([
                    'for' => 'home.order.notify',
                    'channel' => OrderModel::PAY_CHANNEL_ALIPAY,
        ]);

        $data = [
            'out_trade_no' => $order->trade_no,
            'total_fee' => 100 * $order->amount,
            'body' => 'test body - 测试',
        ];

        $pay = Pay::wxpay($config)->scan($data);

        return $pay->code_url;
    }

    private function checkAlipayNotify($data)
    {
        $config = $this->config->alipay;

        if ($data->app_id != $config->app_id) {
            return false;
        }

        $scopes = ['TRADE_SUCCESS', 'TRADE_FINISHED'];

        if (!in_array($data->trade_status, $scopes)) {
            return false;
        }

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findByTradeNo($data->out_trade_no);

        if (!$order) {
            return false;
        }

        if ($order->status != OrderModel::STATUS_PENDING) {
            return false;
        }

        if ($data->total_amount != $order->amount) {
            return false;
        }

        return $order;
    }

    private function checkWxpayNotify($data)
    {
        $config = $this->config->wxpay;

        if ($data->appid != $config->appid) {
            return false;
        }

        if ($data->result_code != 'SUCCESS') {
            return false;
        }

        $orderRepo = new OrderRepo();

        $order = $orderRepo->findByTradeNo($data->out_trade_no);

        if (!$order) {
            return false;
        }

        if ($order->status != OrderModel::STATUS_PENDING) {
            return false;
        }

        if ($data->total_fee != 100 * $order->amount) {
            return false;
        }

        return $order;
    }

    private function alipayNotify()
    {
        $config = $this->config->alipay->toArray();

        $pay = Pay::alipay($config);

        $data = $pay->verify();

        $order = $this->checkAlipayNotify($data);

        if ($order instanceof OrderModel) {

            $this->afterNotify($order);

            $pay->success()->send();
        }
    }

    private function wxpayNotify()
    {
        $config = $this->config->wxpay->toArray();

        $pay = Pay::wxpay($config);

        $data = $pay->verify();

        $order = $this->checkWxpayNotify($data);

        if ($order instanceof OrderModel) {

            $this->afterNotify($order);

            $pay->success()->send();
        }
    }

    private function afterNotify($order)
    {
        $orderRepo = new OrderRepo();

        $orderItems = $orderRepo->findOrderItems($order->id);

        $itemTypes = [
            OrderModel::ITEM_TYPE_COURSE,
            OrderModel::ITEM_TYPE_PACKAGE,
        ];

        if (in_array($order->item_type, $itemTypes)) {

            $courseUserRepo = new CourseUserRepo();

            foreach ($orderItems as $item) {

                $course = json_decode($item->item_info);

                $expireTime = $course->expiry > 0 ? time() + 86400 * $course->expiry : 0;

                $courseUserRepo->create([
                    'user_id' => $order->user_id,
                    'course_id' => $course->id,
                    'expire_time' => $expireTime,
                ]);
            }
        }

        $order->status = OrderModel::STATUS_FINISHED;

        $order->update();
    }

    private function findOrFail($tradeNo)
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findOrFail($tradeNo);

        return $order;
    }

}
