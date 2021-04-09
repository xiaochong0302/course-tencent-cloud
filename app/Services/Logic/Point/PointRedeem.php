<?php

namespace App\Services\Logic\Point;

use App\Library\Utils\Lock as LockUtil;
use App\Models\PointGift as PointGiftModel;
use App\Models\PointRedeem as PointRedeemModel;
use App\Models\Task as TaskModel;
use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\Logic\Point\PointHistory as PointHistoryService;
use App\Services\Logic\PointGiftTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\PointRedeem as PointRedeemValidator;

class PointRedeem extends LogicService
{

    use PointGiftTrait;

    public function handle()
    {
        $giftId = $this->request->getPost('gift_id', ['trim', 'int']);

        $gift = $this->checkPointGift($giftId);

        $user = $this->getLoginUser();

        $validator = new PointRedeemValidator();

        $validator->checkIfAllowRedeem($gift, $user);

        $this->createPointRedeem($gift, $user);
    }

    protected function createPointRedeem(PointGiftModel $gift, UserModel $user)
    {
        $logger = $this->getLogger('point');

        $itemId = "point_redeem:{$gift->id}";

        $lockId = LockUtil::addLock($itemId);

        if ($lockId === false) {
            throw new \RuntimeException('Add Lock Failed');
        }

        try {

            $this->db->begin();

            $redeem = new PointRedeemModel();

            $redeem->user_id = $user->id;
            $redeem->user_name = $user->name;
            $redeem->gift_id = $gift->id;
            $redeem->gift_type = $gift->type;
            $redeem->gift_name = $gift->name;
            $redeem->gift_point = $gift->point;

            if ($gift->type == PointGiftModel::TYPE_GOODS) {
                $userRepo = new UserRepo();
                $contact = $userRepo->findUserContact($user->id);
                $redeem->contact_name = $contact->name;
                $redeem->contact_phone = $contact->phone;
                $redeem->contact_address = $contact->fullAddress();
            }

            $redeem->status = PointRedeemModel::STATUS_PENDING;

            $result = $redeem->create();

            if ($result === false) {
                throw new \RuntimeException('Create Point Redeem Failed');
            }

            $gift->stock -= 1;
            $gift->redeem_count += 1;

            if ($gift->update() === false) {
                throw new \RuntimeException('Decrease Gift Stock Failed');
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
            $task->item_type = TaskModel::TYPE_POINT_GIFT_DELIVER;
            $task->item_info = $itemInfo;

            $result = $task->create();

            if ($result === false) {
                throw new \RuntimeException('Create Gift Deliver Task Failed');
            }

            $this->handleRedeemPoint($redeem);

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger->error('Point Redeem Exception ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }

        LockUtil::releaseLock($itemId, $lockId);
    }

    protected function handleRedeemPoint(PointRedeemModel $redeem)
    {
        $service = new PointHistoryService();

        $service->handlePointRedeem($redeem);
    }

}
