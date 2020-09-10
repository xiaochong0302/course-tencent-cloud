<?php

namespace App\Console\Tasks;

use App\Repos\User as UserRepo;
use App\Services\Search\UserDocument;
use App\Services\Search\UserSearcher;
use App\Services\Syncer\UserIndex as UserIndexSyncer;

class SyncUserIndexTask extends Task
{

    public function mainAction()
    {
        $cache = $this->getCache();

        $redis = $cache->getRedis();

        $key = $this->getSyncKey();

        $userIds = $redis->sRandMember($key, 1000);

        if (!$userIds) return;

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($userIds);

        if ($users->count() == 0) return;

        $document = new UserDocument();

        $handler = new UserSearcher();

        $index = $handler->getXS()->getIndex();

        $index->openBuffer();

        foreach ($users as $user) {

            $doc = $document->setDocument($user);

            if ($user->deleted == 0) {
                $index->update($doc);
            } else {
                $index->del($user->id);
            }
        }

        $index->closeBuffer();

        $redis->sRem($key, ...$userIds);
    }

    protected function getSyncKey()
    {
        $syncer = new UserIndexSyncer();

        return $syncer->getSyncKey();
    }

}
