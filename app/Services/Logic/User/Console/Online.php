<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Models\Online as OnlineModel;
use App\Models\User as UserModel;
use App\Repos\Online as OnlineRepo;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;

class Online extends LogicService
{

    use ClientTrait;

    public function handle(): void
    {
        $user = $this->getLoginUser();

        $now = time();

        if ($now - $user->active_time < 1800) return;

        $this->updateActiveTime($user, $now);
        $this->handleOnlineRecord($user, $now);
    }

    protected function updateActiveTime(UserModel $user, int $activeTime): void
    {
        $user->active_time = $activeTime;

        $user->update();
    }

    protected function handleOnlineRecord(UserModel $user, int $activeTime): void
    {
        $clientType = $this->getClientType();
        $clientIp = $this->getClientIp();
        $matchedRecord = $this->findMatchedRecord($user->id, $clientType, $clientIp);

        if ($matchedRecord) {
            $matchedRecord->active_time = $activeTime;
            $matchedRecord->update();
        } else {
            $this->createOnline($user->id, $clientType, $clientIp, $activeTime);
        }
    }

    protected function findMatchedRecord(int $userId, int $clientType, string $clientIp): ?OnlineModel
    {
        $onlineRepo = new OnlineRepo();

        $records = $onlineRepo->findByUserDate($userId, date('Ymd'));

        if ($records->count() == 0) return null;

        foreach ($records as $record) {
            if ($record->client_type == $clientType && $record->client_ip == $clientIp) {
                return $record;
            }
        }

        return null;
    }

    protected function createOnline(int $userId, int $clientType, string $clientIp, int $activeTime): OnlineModel
    {
        $online = new OnlineModel();

        $online->user_id = $userId;
        $online->client_type = $clientType;
        $online->client_ip = $clientIp;
        $online->active_time = $activeTime;

        $online->create();

        return $online;
    }

}
