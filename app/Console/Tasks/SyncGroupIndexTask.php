<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Repos\ImGroup as GroupRepo;
use App\Services\Search\GroupDocument;
use App\Services\Search\GroupSearcher;
use App\Services\Sync\GroupIndex as GroupIndexSync;

class SyncGroupIndexTask extends Task
{

    public function mainAction()
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $groupIds = $redis->sRandMember($key, 1000);

        if (!$groupIds) return;

        $groupRepo = new GroupRepo();

        $groups = $groupRepo->findByIds($groupIds);

        if ($groups->count() == 0) return;

        $document = new GroupDocument();

        $handler = new GroupSearcher();

        $index = $handler->getXS()->getIndex();

        $index->openBuffer();

        foreach ($groups as $group) {

            $doc = $document->setDocument($group);

            if ($group->published == 1) {
                $index->update($doc);
            } else {
                $index->del($group->id);
            }
        }

        $index->closeBuffer();

        $redis->sRem($key, ...$groupIds);
    }

    protected function getSyncKey()
    {
        $sync = new GroupIndexSync();

        return $sync->getSyncKey();
    }

}
