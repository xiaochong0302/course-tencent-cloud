<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Article as ArticleModel;
use App\Models\Course as CourseModel;
use App\Models\ExamPaper as ExamPaperModel;
use App\Models\KgProduct as KgProductModel;
use App\Models\KgPayment as KgPaymentModel;
use App\Models\Order as OrderModel;
use App\Models\Package as PackageModel;
use App\Models\Refund as RefundModel;
use App\Models\Trade as TradeModel;
use App\Models\Vip as VipModel;
use App\Repos\Order as OrderRepo;

class Order extends Validator
{

    public function checkById(int $id): OrderModel
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findById($id);

        if (!$order) {
            throw new BadRequestException('order.not_found');
        }

        return $order;
    }

    public function checkBySn(string $sn): OrderModel
    {
        $orderRepo = new OrderRepo();

        $order = $orderRepo->findBySn($sn);

        if (!$order) {
            throw new BadRequestException('order.not_found');
        }

        return $order;
    }

    public function checkItemType(int $type): int
    {
        $types = OrderModel::itemTypes();

        if (!array_key_exists($type, $types)) {
            throw new BadRequestException('order.invalid_item_type');
        }

        return $type;
    }

    public function checkCourse(int $id): CourseModel
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkPackage(int $id): PackageModel
    {
        $validator = new Package();

        return $validator->checkPackage($id);
    }

    public function checkVip(int $id): VipModel
    {
        $validator = new Vip();

        return $validator->checkVip($id);
    }

    public function checkExamPaper(int $id): ExamPaperModel
    {
        $validator = new ExamPaper();

        return $validator->checkExamPaper($id);
    }

    public function checkArticle(int $id): ArticleModel
    {
        $validator = new ChapterRead();

        return $validator->checkArticle($id);
    }

    public function checkAmount(float $amount): float
    {
        if ($amount < 0.01 || $amount > 100000) {
            throw new BadRequestException('order.invalid_amount');
        }

        return $amount;
    }

    public function checkStatus(int $status): int
    {
        $list = OrderModel::statusTypes();

        if (!array_key_exists($status, $list)) {
            throw new BadRequestException('order.invalid_status');
        }

        return $status;
    }

    public function checkIfAllowPay(OrderModel $order): void
    {
        if ($order->status != OrderModel::STATUS_PENDING) {
            throw new BadRequestException('order.pay_not_allowed');
        }
    }

    public function checkIfAllowCancel(OrderModel $order): void
    {
        if ($order->status != OrderModel::STATUS_PENDING) {
            throw new BadRequestException('order.cancel_not_allowed');
        }
    }

    public function checkIfAllowRefund(OrderModel $order): void
    {
        if ($order->status != OrderModel::STATUS_FINISHED) {
            throw new BadRequestException('order.refund_not_allowed');
        }

        $types = [
            KgProductModel::ITEM_COURSE,
            KgProductModel::ITEM_PACKAGE,
            KgProductModel::ITEM_EXAM_PAPER,
        ];

        if (!in_array($order->item_type, $types)) {
            throw new BadRequestException('order.refund_not_supported');
        }

        $channels = [
            KgPaymentModel::CHANNEL_WXPAY_VIRTUAL_CASH,
            KgPaymentModel::CHANNEL_WXPAY_VIRTUAL_COIN,
        ];

        // 微信虚拟支付不支持退款
        if (in_array($order->channel, $channels)) {
            throw new BadRequestException('order.refund_not_supported');
        }

        $orderRepo = new OrderRepo();

        $trade = $orderRepo->findLastTrade($order->id);

        if ($trade->status != TradeModel::STATUS_FINISHED) {
            throw new BadRequestException('order.refund_not_allowed');
        }

        $refund = $orderRepo->findLastRefund($order->id);

        $scopes = [
            RefundModel::STATUS_PENDING,
            RefundModel::STATUS_APPROVED,
        ];

        if ($refund && in_array($refund->status, $scopes)) {
            throw new BadRequestException('order.refund_request_existed');
        }
    }

    public function checkIfBoughtCourse(int $userId, int $courseId): void
    {
        $orderRepo = new OrderRepo();

        $itemType = KgProductModel::ITEM_COURSE;

        $order = $orderRepo->findUserLastDeliveringOrder($userId, $courseId, $itemType);

        if ($order) {
            throw new BadRequestException('order.is_delivering');
        }

        $order = $orderRepo->findUserLastFinishedOrder($userId, $courseId, $itemType);

        if ($order && $order->item_info['course']['study_expiry_time'] > time()) {
            throw new BadRequestException('order.has_bought_course');
        }
    }

    public function checkIfBoughtPackage(int $userId, int $packageId): void
    {
        $orderRepo = new OrderRepo();

        $itemType = KgProductModel::ITEM_PACKAGE;

        $order = $orderRepo->findUserLastDeliveringOrder($userId, $packageId, $itemType);

        if ($order) {
            throw new BadRequestException('order.is_delivering');
        }

        $order = $orderRepo->findUserLastFinishedOrder($userId, $packageId, $itemType);

        if ($order) {
            throw new BadRequestException('order.has_bought_package');
        }
    }

    public function checkIfBoughtExamPaper(int $userId, int $paperId): void
    {
        $orderRepo = new OrderRepo();

        $itemType = KgProductModel::ITEM_EXAM_PAPER;

        $order = $orderRepo->findUserLastDeliveringOrder($userId, $paperId, $itemType);

        if ($order) {
            throw new BadRequestException('order.is_delivering');
        }

        $order = $orderRepo->findUserLastFinishedOrder($userId, $paperId, $itemType);

        if ($order && $order->item_info['exam_paper']['study_expiry_time'] > time()) {
            throw new BadRequestException('order.has_bought_exam_paper');
        }
    }

    public function checkIfBoughtArticle(int $userId, int $articleId): void
    {
        $orderRepo = new OrderRepo();

        $itemType = KgProductModel::ITEM_ARTICLE;

        $order = $orderRepo->findUserLastDeliveringOrder($userId, $articleId, $itemType);

        if ($order) {
            throw new BadRequestException('order.is_delivering');
        }

        $order = $orderRepo->findUserLastFinishedOrder($userId, $articleId, $itemType);

        if ($order) {
            throw new BadRequestException('order.has_bought_article');
        }
    }

}
