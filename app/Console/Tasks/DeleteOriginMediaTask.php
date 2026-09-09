<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Models\ChapterVod as ChapterVodModel;
use App\Services\Vod as VodService;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class DeleteOriginMediaTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $vodSettings = $this->getSettings('vod');

        if ($vodSettings['keep_origin_media'] == 1) return;

        $chapterVods = $this->findChapterVods();

        if ($chapterVods->count() == 0) return;

        echo sprintf('total media: %d', $chapterVods->count()) . PHP_EOL;

        foreach ($chapterVods as $chapterVod) {

            if (!empty($chapterVod->file_encrypt) || !empty($chapterVod->file_transcode)) {

                $chapterVod->file_origin = [];

                $chapterVod->update();

                $this->deleteOriginMedia($chapterVod->file_id);

                echo sprintf('delete media: %s ok', $chapterVod->file_id) . PHP_EOL;
            }
        }

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    protected function deleteOriginMedia(string $fileId): void
    {
        $service = new VodService();

        $deleteParts = [
            ['Type' => 'OriginalFiles']
        ];

        $service->deleteMedia($fileId, $deleteParts);
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|ChapterVodModel[]
     */
    protected function findChapterVods(int $limit = 100)
    {
        return ChapterVodModel::query()
            ->where('file_origin != :file_origin:', ['file_origin' => '[]'])
            ->limit($limit)
            ->execute();
    }

}
