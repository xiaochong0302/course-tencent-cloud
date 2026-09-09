<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Repos\Chapter as ChapterRepo;
use App\Services\Search\ChapterDocument;
use App\Services\Search\ChapterSearcher;
use App\Services\Sync\ChapterIndex as ChapterIndexSync;

class SyncChapterIndexTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $chapterIds = $redis->sRandMember($key, 5000);

        if (empty($chapterIds)) return;

        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findByIds($chapterIds);

        if ($chapters->count() == 0) return;

        echo '------ start sync chapter index ------' . PHP_EOL;

        $document = new ChapterDocument();

        $handler = new ChapterSearcher();

        $index = $handler->getXS()->getIndex();

        $index->openBuffer();

        foreach ($chapters as $chapter) {

            $doc = $document->setDocument($chapter);

            if ($chapter->published == 1) {
                $index->update($doc);
            } else {
                $index->del($chapter->id);
            }
        }

        $index->closeBuffer();

        $redis->sRem($key, ...$chapterIds);

        echo '------ end sync chapter index ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    protected function getSyncKey(): string
    {
        $sync = new ChapterIndexSync();

        return $sync->getSyncKey();
    }

}
