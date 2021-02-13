<?php

namespace App\Services\Logic\Point;

use App\Models\ChapterUser as ChapterUserModel;
use App\Models\ImMessage as ImMessageModel;
use App\Models\Order as OrderModel;
use App\Models\PointHistory as PointHistoryModel;
use App\Models\PointRedeem as PointRedeemModel;
use App\Models\Review as ReviewModel;
use App\Models\User as UserModel;
use App\Models\UserBalance as UserBalanceModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\PointHistory as PointHistoryRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Service;

class PointHistory extends Service
{

    public function handleOrderConsume(OrderModel $order)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $ruleEnabled = $setting['consume_rule']['enabled'] ?? 0;

        if ($ruleEnabled == 0) return;

        $ruleRate = $setting['consume_rule']['rate'] ?? 0;

        if ($ruleRate <= 0) return;

        $eventId = $order->id;
        $eventType = PointHistoryModel::EVENT_ORDER_CONSUME;
        $eventPoint = $ruleRate * $order->amount;

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($order->owner_id);

        $eventInfo = [
            'order' => [
                'sn' => $order->sn,
                'subject' => $order->subject,
                'amount' => $order->amount,
            ]
        ];

        $history = new PointHistoryModel();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $eventId;
        $history->event_type = $eventType;
        $history->event_point = $eventPoint;
        $history->event_info = $eventInfo;

        $this->handlePointHistory($history);
    }

    public function handlePointRedeem(PointRedeemModel $redeem)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventId = $redeem->id;
        $eventType = PointHistoryModel::EVENT_POINT_REDEEM;
        $eventPoint = 0 - $redeem->gift_point;

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($redeem->user_id);

        $eventInfo = [
            'point_redeem' => [
                'id' => $redeem->id,
                'gift_id' => $redeem->gift_id,
                'gift_name' => $redeem->gift_name,
                'gift_type' => $redeem->gift_type,
                'gift_point' => $redeem->gift_point,
            ]
        ];

        $history = new PointHistoryModel();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $eventId;
        $history->event_type = $eventType;
        $history->event_point = $eventPoint;
        $history->event_info = $eventInfo;

        $this->handlePointHistory($history);
    }

    public function handlePointRefund(PointRedeemModel $redeem)
    {
        $eventId = $redeem->id;
        $eventType = PointHistoryModel::EVENT_POINT_REFUND;
        $eventPoint = $redeem->gift_point;

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($redeem->user_id);

        $eventInfo = [
            'point_redeem' => [
                'id' => $redeem->id,
                'gift_id' => $redeem->gift_id,
                'gift_name' => $redeem->gift_name,
                'gift_type' => $redeem->gift_type,
                'gift_point' => $redeem->gift_point,
            ]
        ];

        $history = new PointHistoryModel();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $eventId;
        $history->event_type = $eventType;
        $history->event_point = $eventPoint;
        $history->event_info = $eventInfo;

        $this->handlePointHistory($history);
    }

    public function handleSiteVisit(UserModel $user)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = json_decode($setting['event_rule'], true);

        $eventEnabled = $eventRule['site_visit']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['site_visit']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $eventId = $user->id;
        $eventType = PointHistoryModel::EVENT_SITE_VISIT;
        $eventInfo = '每日访问';

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findDailyEventHistory($eventId, $eventType, date('Ymd'));

        if ($history) return;

        $history = new PointHistoryModel();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $eventId;
        $history->event_type = $eventType;
        $history->event_point = $eventPoint;
        $history->event_info = $eventInfo;

        $this->handlePointHistory($history);
    }

    public function handleAccountRegister(UserModel $user)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = json_decode($setting['event_rule'], true);

        $eventEnabled = $eventRule['account_register']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['account_register']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $eventId = $user->id;
        $eventType = PointHistoryModel::EVENT_ACCOUNT_REGISTER;
        $eventInfo = '帐号注册';

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findDailyEventHistory($eventId, $eventType, date('Ymd'));

        if ($history) return;

        $history = new PointHistoryModel();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $user->id;
        $history->event_type = $eventType;
        $history->event_point = $eventPoint;
        $history->event_info = $eventInfo;

        $this->handlePointHistory($history);
    }

    public function handleChapterStudy(ChapterUserModel $chapterUser)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = json_decode($setting['event_rule'], true);

        $eventEnabled = $eventRule['chapter_study']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['chapter_study']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $eventId = $chapterUser->id;
        $eventType = PointHistoryModel::EVENT_CHAPTER_STUDY;

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($chapterUser->user_id);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapterUser->course_id);

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($chapterUser->chapter_id);

        $eventInfo = [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
            ],
            'chapter' => [
                'id' => $chapter->id,
                'title' => $chapter->title,
            ]
        ];

        $history = new PointHistoryModel();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $eventId;
        $history->event_type = $eventType;
        $history->event_info = $eventInfo;
        $history->event_point = $eventPoint;

        $this->handlePointHistory($history);
    }

    public function handleCourseReview(ReviewModel $review)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = json_decode($setting['event_rule'], true);

        $eventEnabled = $eventRule['course_review']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['course_review']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $eventId = $review->id;
        $eventType = PointHistoryModel::EVENT_COURSE_REVIEW;

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findEventHistory($eventId, $eventType);

        if ($history) return;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($review->owner_id);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($review->course_id);

        $eventInfo = [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
            ]
        ];

        $history = new PointHistoryModel();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $eventId;
        $history->event_type = $eventType;
        $history->event_info = $eventInfo;
        $history->event_point = $eventPoint;

        $this->handlePointHistory($history);
    }

    public function handleImDiscuss(ImMessageModel $message)
    {
        $setting = $this->getSettings('point');

        $pointEnabled = $setting['enabled'] ?? 0;

        if ($pointEnabled == 0) return;

        $eventRule = json_decode($setting['event_rule'], true);

        $eventEnabled = $eventRule['im_discuss']['enabled'] ?? 0;

        if ($eventEnabled == 0) return;

        $eventPoint = $eventRule['im_discuss']['point'] ?? 0;

        if ($eventPoint <= 0) return;

        $eventId = $message->sender_id;
        $eventType = PointHistoryModel::EVENT_IM_DISCUSS;
        $eventInfo = '每日微聊';

        $historyRepo = new PointHistoryRepo();

        $history = $historyRepo->findDailyEventHistory($eventId, $eventType, date('Ymd'));

        if ($history) return;

        $userRepo = new UserRepo();

        $user = $userRepo->findById($message->sender_id);

        $history = new PointHistoryModel();

        $history->user_id = $user->id;
        $history->user_name = $user->name;
        $history->event_id = $eventId;
        $history->event_type = $eventType;
        $history->event_info = $eventInfo;
        $history->event_point = $eventPoint;

        $this->handlePointHistory($history);
    }

    protected function handlePointHistory(PointHistoryModel $history)
    {
        $logger = $this->getLogger('point');

        try {

            $this->db->begin();

            if ($history->create() === false) {
                throw new \RuntimeException('Create Point History Failed');
            }

            $userRepo = new UserRepo();

            $balance = $userRepo->findUserBalance($history->user_id);

            if ($balance) {
                $balance->user_id = $history->user_id;
                $balance->point += $history->event_point;
                $result = $balance->update();
            } else {
                $balance = new UserBalanceModel();
                $balance->user_id = $history->user_id;
                $balance->point = $history->event_point;
                $result = $balance->create();
            }

            if ($result === false) {
                throw new \RuntimeException('Save User Balance Failed');
            }

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger->error('Point History Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

}
