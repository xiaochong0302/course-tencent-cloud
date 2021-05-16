<?php

namespace App\Repos;

use App\Models\Answer as AnswerModel;
use App\Models\Article as ArticleModel;
use App\Models\Comment as CommentModel;
use App\Models\Online as OnlineModel;
use App\Models\Order as OrderModel;
use App\Models\OrderStatus as OrderStatusModel;
use App\Models\PointRedeem as PointRedeemModel;
use App\Models\Question as QuestionModel;
use App\Models\Refund as RefundModel;
use App\Models\User as UserModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Stat extends Repository
{

    public function countPendingArticles()
    {
        return (int)ArticleModel::count([
            'conditions' => 'published = :published: AND deleted = 0',
            'bind' => ['published' => ArticleModel::PUBLISH_PENDING],
        ]);
    }

    public function countPendingQuestions()
    {
        return (int)ArticleModel::count([
            'conditions' => 'published = :published: AND deleted = 0',
            'bind' => ['published' => QuestionModel::PUBLISH_PENDING],
        ]);
    }

    public function countPendingAnswers()
    {
        return (int)AnswerModel::count([
            'conditions' => 'published = :published: AND deleted = 0',
            'bind' => ['published' => AnswerModel::PUBLISH_PENDING],
        ]);
    }

    public function countPendingComments()
    {
        return (int)CommentModel::count([
            'conditions' => 'published = :published: AND deleted = 0',
            'bind' => ['published' => CommentModel::PUBLISH_PENDING],
        ]);
    }

    public function countDailyRegisteredUsers($date)
    {
        $startTime = strtotime($date);

        $endTime = $startTime + 86400;

        return (int)UserModel::count([
            'conditions' => 'create_time BETWEEN :start_time: AND :end_time:',
            'bind' => ['start_time' => $startTime, 'end_time' => $endTime],
        ]);
    }

    public function countDailyOnlineUser($date)
    {
        $startTime = strtotime($date);

        $endTime = $startTime + 86400;

        return (int)OnlineModel::count([
            'conditions' => 'active_time BETWEEN :start_time: AND :end_time:',
            'bind' => ['start_time' => $startTime, 'end_time' => $endTime],
        ]);
    }

    public function countDailySales($date)
    {
        $sql = "SELECT count(*) AS total_count FROM %s AS os JOIN %s AS o ON os.order_id = o.id ";

        $sql .= "WHERE os.status = ?1 AND o.create_time BETWEEN ?2 AND ?3";

        $phql = sprintf($sql, OrderStatusModel::class, OrderModel::class);

        $startTime = strtotime($date);

        $endTime = $startTime + 86400;

        $result = $this->modelsManager->executeQuery($phql, [
            1 => OrderModel::STATUS_FINISHED,
            2 => $startTime,
            3 => $endTime,
        ]);

        return (float)$result[0]['total_count'];
    }

    public function countDailyRefunds($date)
    {
        $startTime = strtotime($date);

        $endTime = $startTime + 86400;

        return (int)RefundModel::count([
            'conditions' => 'status = ?1 AND create_time BETWEEN ?2 AND ?3',
            'bind' => [
                1 => RefundModel::STATUS_FINISHED,
                2 => $startTime,
                3 => $endTime,
            ],
        ]);
    }

    public function countDailyPointRedeems($date)
    {
        $startTime = strtotime($date);

        $endTime = $startTime + 86400;

        return (int)PointRedeemModel::count([
            'conditions' => 'status = ?1 AND create_time BETWEEN ?2 AND ?3',
            'bind' => [
                1 => PointRedeemModel::STATUS_PENDING,
                2 => $startTime,
                3 => $endTime,
            ],
        ]);
    }

    public function sumDailySales($date)
    {
        $sql = "SELECT sum(o.amount) AS total_amount FROM %s AS os JOIN %s AS o ON os.order_id = o.id ";

        $sql .= "WHERE os.status = ?1 AND o.create_time BETWEEN ?2 AND ?3";

        $phql = sprintf($sql, OrderStatusModel::class, OrderModel::class);

        $startTime = strtotime($date);

        $endTime = $startTime + 86400;

        $result = $this->modelsManager->executeQuery($phql, [
            1 => OrderModel::STATUS_FINISHED,
            2 => $startTime,
            3 => $endTime,
        ]);

        return (float)$result[0]['total_amount'];
    }

    public function sumDailyRefunds($date)
    {
        $startTime = strtotime($date);

        $endTime = $startTime + 86400;

        return (float)RefundModel::sum([
            'column' => 'amount',
            'conditions' => 'status = ?1 AND create_time BETWEEN ?2 AND ?3',
            'bind' => [
                1 => RefundModel::STATUS_FINISHED,
                2 => $startTime,
                3 => $endTime,
            ],
        ]);
    }

    /**
     * @param int $type
     * @param int $year
     * @param int $month
     * @return ResultsetInterface|Resultset|OrderModel[]
     */
    public function findMonthlyOrders($type, $year, $month)
    {
        $startTime = strtotime("{$year}-{$month}");

        $endTime = strtotime('+1 month', $startTime);

        $status = OrderModel::STATUS_FINISHED;

        return $this->modelsManager->createBuilder()
            ->addFrom(OrderStatusModel::class, 'os')
            ->join(OrderModel::class, 'os.order_id = o.id', 'o')
            ->columns('o.*')
            ->where('o.item_type = :type:', ['type' => $type])
            ->andWhere('os.status = :status:', ['status' => $status])
            ->betweenWhere('o.create_time', $startTime, $endTime)
            ->getQuery()->execute();
    }

}
