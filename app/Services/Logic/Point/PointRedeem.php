<?php

namespace App\Services\Logic\Point;

use App\Models\PointGift as PointGiftModel;
use App\Models\PointHistory as PointHistoryModel;
use App\Models\PointRedeem as PointRedeemModel;
use App\Models\Task as TaskModel;
use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\Logic\PointGiftTrait;
use App\Services\Logic\Service;
use App\Validators\PointRedeem as PointRedeemValidator;

class PointRedeem extends Service
{

    use PointGiftTrait;

    public function handle()
    {
        $giftId = $this->request->getPost('gift_id', ['trim', 'int']);

        $gift = $this->checkGift($giftId);

        $user = $this->getLoginUser();

        $validator = new PointRedeemValidator();

        $validator->checkIfAllowRedeem($gift, $user);

        $this->createPointRedeem($gift, $user);
    }

    protected function createPointRedeem(PointGiftModel $gift, UserModel $user)
    {
        $userRepo = new UserRepo();

        $balance = $userRepo->findUserBalance($user->id);

        try {

            $this->db->begin();

            $redeem = new PointRedeemModel();

            $redeem->gift_id = $gift->id;
            $redeem->gift_type = $gift->type;
            $redeem->gift_name = $gift->name;
            $redeem->gift_point = $gift->point;

            $result = $redeem->create();

            if ($result === false) {
                throw new \RuntimeException('Create Point Redeem Failed');
            }

            $task = new TaskModel();

            $itemInfo = [
                'point_redeem' => [
                    'id' => $redeem->id,
                    'user_id' => $redeem->user_id,
                    'gift_id' => $redeem->gift_id,
                ]
            ];

            $task->item_id = $redeem->id;
            $task->item_type = TaskModel::TYPE_POINT_GIFT_AWARD;
            $task->item_info = $itemInfo;

            $result = $task->create();

            if ($result === false) {
                throw new \RuntimeException('Create Async Task Failed');
            }

            $history = new PointHistoryModel();

            $eventInfo = [
                'gift' => [
                    'id' => $gift->id,
                    'name' => $gift->name,
                ]
            ];

            $history->user_id = $user->id;
            $history->user_name = $user->name;
            $history->event_id = $gift->id;
            $history->event_type = PointHistoryModel::EVENT_POINT_REDEEM;
            $history->event_point = $gift->point;
            $history->event_info = $eventInfo;

            $result = $history->create();

            if ($result === false) {
                throw new \RuntimeException('Create Point History Failed');
            }

            $balance->point -= $gift->point;

            $result = $balance->update();

            if ($result === false) {
                throw new \RuntimeException('Update User Balance Failed');
            }

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $this->logger->error('Point Redeem Exception ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'balance' => ['user_id' => $balance->id, 'point' => $balance->point],
                    'gift' => ['id' => $gift->id, 'point' => $gift->point],
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

}
