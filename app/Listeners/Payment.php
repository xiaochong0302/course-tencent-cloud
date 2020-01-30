<?php

namespace App\Listeners;

use App\Models\CourseUser as CourseUserModel;
use App\Models\Order as OrderModel;
use App\Models\Trade as TradeModel;
use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;
use Phalcon\Events\Event;

class Payment extends Listener
{

    protected $logger;

    public function __construct()
    {
        $this->logger = $this->getLogger();
    }

    public function afterPay(Event $event, $source, TradeModel $trade)
    {
        try {

            $this->db->begin();

            $trade->status = TradeModel::STATUS_FINISHED;

            if ($trade->update() === false) {
                throw new \RuntimeException('Update Trade Status Failed');
            }

            $orderRepo = new OrderRepo();

            $order = $orderRepo->findBySn($trade->order_sn);

            $order->status = OrderModel::STATUS_FINISHED;

            if ($order->update() === false) {
                throw new \RuntimeException('Update Order Status Failed');
            }

            switch ($order->item_type) {
                case OrderModel::TYPE_COURSE:
                    $this->handleCourseOrder($order);
                    break;
                case OrderModel::TYPE_PACKAGE:
                    $this->handlePackageOrder($order);
                    break;
                case OrderModel::TYPE_REWARD:
                    $this->handleRewardOrder($order);
                    break;
                case OrderModel::TYPE_VIP:
                    $this->handleVipOrder($order);
                    break;
                case OrderModel::TYPE_TEST:
                    $this->handleTestOrder($order);
                    break;
            }

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $this->logger->error('After Pay Event Exception {msg}',
                ['msg' => $e->getMessage()]
            );
        }

        $this->logger->debug('Event: {event}, Source: {source}, Data: {data}', [
            'event' => $event->getType(),
            'source' => get_class($source),
            'data' => kg_json_encode($trade),
        ]);
    }

    protected function handleCourseOrder(OrderModel $order)
    {
        $courseUser = new CourseUserModel();

        $courseUser->user_id = $order->user_id;
        $courseUser->course_id = $order->item_id;
        $courseUser->expire_time = $order->item_info['course']['expire_time'];
        $courseUser->role_type = CourseUserModel::ROLE_STUDENT;
        $courseUser->source_type = CourseUserModel::SOURCE_PAID;

        if ($courseUser->create() === false) {
            throw new \RuntimeException('Create CourseSearcher User Failed');
        }
    }

    protected function handlePackageOrder(OrderModel $order)
    {
        foreach ($order->item_info['courses'] as $course) {

            $courseUser = new CourseUserModel();

            $courseUser->user_id = $order->user_id;
            $courseUser->course_id = $course['id'];
            $courseUser->expire_time = $course['expire_time'];
            $courseUser->role_type = CourseUserModel::ROLE_STUDENT;
            $courseUser->source_type = CourseUserModel::SOURCE_PAID;

            if ($courseUser->create() === false) {
                throw new \RuntimeException('Create CourseSearcher User Failed');
            }
        }
    }

    protected function handleVipOrder(OrderModel $order)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->user_id);

        $baseTime = $user->vip_expiry > time() ? $user->vip_expiry : time();

        switch ($order->item_info['vip']['duration']) {
            case 'one_month':
                $user->vip_expiry = strtotime('+1 months', $baseTime);
                break;
            case 'three_month':
                $user->vip_expiry = strtotime('+3 months', $baseTime);
                break;
            case 'six_month':
                $user->vip_expiry = strtotime('+6 months', $baseTime);
                break;
            case 'twelve_month':
                $user->vip_expiry = strtotime('+12 months', $baseTime);
                break;
        }

        if ($user->update() === false) {
            throw new \RuntimeException('Update Vip Expiry Failed');
        }
    }

    protected function handleRewardOrder(OrderModel $order)
    {

    }

    protected function handleTestOrder(OrderModel $order)
    {

    }

}