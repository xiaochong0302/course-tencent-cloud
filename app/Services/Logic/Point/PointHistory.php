<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Point;

use App\Models\PointHistory as PointHistoryModel;
use App\Models\UserBalance as UserBalanceModel;
use App\Repos\User as UserRepo;
use App\Services\Logic\Service as LogicService;

class PointHistory extends LogicService
{

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
