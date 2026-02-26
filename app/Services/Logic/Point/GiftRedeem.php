<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point;

use App\Library\Utils\Lock as LockUtil;
use App\Models\KgSale as KgSaleModel;
use App\Models\PointGift as PointGiftModel;
use App\Models\PointGiftRedeem as PointGiftRedeemModel;
use App\Models\Task as TaskModel;
use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\Logic\Point\History\PointGiftRedeem as PointGiftRedeemPointHistory;
use App\Services\Logic\PointGiftTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\PointGiftRedeem as PointGiftRedeemValidator;

class GiftRedeem extends LogicService
{

    use PointGiftTrait;

    public function handle($id)
    {
        $gift = $this->checkPointGift($id);

        $user = $this->getLoginUser();

        $validator = new PointGiftRedeemValidator();

        $validator->checkIfAllowRedeem($gift, $user);

        $this->createGiftRedeem($gift, $user);
    }

    protected function createGiftRedeem(PointGiftModel $gift, UserModel $user)
    {
        $itemId = "point_gift_redeem:{$gift->id}";

        $lockId = LockUtil::addLock($itemId);

        if ($lockId === false) {
            throw new \RuntimeException('Add Lock Failed');
        }

        try {

            $this->db->begin();

            $redeem = new PointGiftRedeemModel();

            $redeem->user_id = $user->id;
            $redeem->user_name = $user->name;
            $redeem->gift_id = $gift->id;
            $redeem->gift_type = $gift->type;
            $redeem->gift_name = $gift->name;
            $redeem->gift_point = $gift->point;

            if ($gift->type == KgSaleModel::ITEM_GOODS) {
                $userRepo = new UserRepo();
                $contact = $userRepo->findUserContact($user->id);
                $redeem->contact_name = $contact->name;
                $redeem->contact_phone = $contact->phone;
                $redeem->contact_address = $contact->fullAddress();
            }

            $redeem->status = PointGiftRedeemModel::STATUS_PENDING;
            $redeem->create();

            $gift->stock -= 1;
            $gift->redeem_count += 1;
            $gift->update();

            $task = new TaskModel();

            $task->item_id = $redeem->id;
            $task->item_type = TaskModel::TYPE_POINT_GIFT_DELIVER;
            $task->create();

            $this->handleRedeemPoint($redeem);

            $this->db->commit();

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger = $this->getLogger('point');

            $logger->error('Create Gift Redeem Exception: ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }

        LockUtil::releaseLock($itemId, $lockId);
    }

    protected function handleRedeemPoint(PointGiftRedeemModel $redeem)
    {
        $service = new PointGiftRedeemPointHistory();

        $service->handle($redeem);
    }

}
