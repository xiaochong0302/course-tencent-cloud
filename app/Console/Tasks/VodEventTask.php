<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Library\Utils\Lock as LockUtil;
use App\Models\Chapter as ChapterModel;
use App\Repos\Chapter as ChapterRepo;
use App\Services\ChapterVod as ChapterVodService;
use App\Services\CourseStat as CourseStatService;
use App\Services\Vod as VodService;
use TencentCloud\Vod\V20180717\Models\EventContent;
use TencentCloud\Vod\V20180717\Models\MediaProcessTaskResult;

class VodEventTask extends Task
{

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $events = $this->pullEvents();

        if (!$events) return;

        echo '------ start vod event task ------' . PHP_EOL;

        $handles = [];

        $logger = $this->getLogger('vod');

        foreach ($events as $event) {

            $result = true;

            if ($event->EventType == 'NewFileUpload') {
                $result = $this->handleNewFileUploadEvent($event);
            } elseif ($event->EventType == 'ProcedureStateChanged') {
                $result = $this->handleProcedureStateChangedEvent($event);
            } elseif ($event->EventType == 'FileDeleted') {
                $result = $this->handleFileDeletedEvent($event);
            }

            if ($result) {
                $handles[] = $event->EventHandle;
            } else {
                $logger->error("Handle {$event->EventType} Event Failed: " . kg_json_encode($event));
            }
        }

        if (count($handles) > 0) {
            $this->confirmEvents($handles);
        }

        echo '------ end vod event task ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    protected function handleNewFileUploadEvent(EventContent $event): bool
    {
        $fileId = $event->FileUploadEvent->FileId ?? 0;

        if ($fileId == 0) return false;

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findByFileId($fileId);

        if (!$chapter) return false;

        $metaData = $event->FileUploadEvent->MetaData;

        $width = $metaData->Height ?? 0;
        $height = $metaData->Width ?? 0;
        $duration = $metaData->Duration ?? 0;

        /**
         * 获取不到时长，尝试通过主动查询获取
         */
        if ($duration == 0) {
            $duration = $this->getFileDuration($fileId);
        }

        $isVideo = $width > 0 && $height > 0;

        $attrs = $chapter->attrs;

        $vodService = new VodService();

        $standardStatus = $attrs['transcode']['standard']['status'] ?? null;

        if ($standardStatus == ChapterModel::TRANS_STATUS_CREATED) {
            if ($duration > 0) {
                $status = ChapterModel::TRANS_STATUS_PROCESSING;
                if ($isVideo) {
                    $vodService->createTransVideoTask($fileId);
                } else {
                    $vodService->createTransAudioTask($fileId);
                }
            } else {
                $status = ChapterModel::TRANS_STATUS_FAILED;
            }
            $attrs['transcode']['standard']['status'] = $status;
        }

        $encryptStatus = $attrs['transcode']['encrypt']['status'] ?? null;

        if ($encryptStatus == ChapterModel::TRANS_STATUS_CREATED) {
            if ($duration > 0) {
                if ($isVideo) {
                    $vodService->createEncryptVideoTask($fileId);
                }
                $status = ChapterModel::TRANS_STATUS_PROCESSING;
            } else {
                $status = ChapterModel::TRANS_STATUS_FAILED;
            }
            $attrs['transcode']['encrypt']['status'] = $status;
        }

        $attrs['duration'] = (int)$duration;

        $chapter->attrs = $attrs;

        $chapter->update();

        $this->pullMediaInfo($chapter->id);

        $this->updateCourseVodAttrs($chapter->course_id);

        return true;
    }

    protected function handleProcedureStateChangedEvent(EventContent $event): bool
    {
        $fileId = $event->ProcedureStateChangeEvent->FileId ?? 0;

        if ($fileId == 0) return false;

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findByFileId($fileId);

        if (!$chapter) return false;

        $attrs = $chapter->attrs;

        /**
         * 获取不到时长，尝试通过接口获得
         */
        if ($attrs['duration'] == 0) {
            $attrs['duration'] = $this->getFileDuration($fileId);
        }

        /**
         * @var $processResult MediaProcessTaskResult[]
         */
        $processResult = $event->ProcedureStateChangeEvent->MediaProcessResultSet ?? [];

        $standardSuccessCount = 0;
        $standardFailCount = 0;
        $encryptSuccessCount = 0;
        $encryptFailCount = 0;

        if ($processResult) {
            foreach ($processResult as $item) {
                if ($item->Type == 'Transcode') {
                    $status = $item->TranscodeTask->Status;
                    if ($status == 'SUCCESS') {
                        $standardSuccessCount++;
                    } elseif ($status == 'FAIL') {
                        $standardFailCount++;
                    }
                } elseif ($item->Type == 'AdaptiveDynamicStreaming') {
                    $status = $item->AdaptiveDynamicStreamingTask->Status;
                    if ($status == 'SUCCESS') {
                        $encryptSuccessCount++;
                    } elseif ($status == 'FAIL') {
                        $encryptFailCount++;
                    }
                }
            }
        }

        $status = ChapterModel::TRANS_STATUS_PROCESSING;

        if (!$processResult) {
            $status = ChapterModel::TRANS_STATUS_FAILED;
        }

        $standardStatus = $attrs['transcode']['standard']['status'] ?? null;

        if ($standardStatus == ChapterModel::TRANS_STATUS_PROCESSING) {
            if ($standardSuccessCount > 0) {
                $status = ChapterModel::TRANS_STATUS_FINISHED;
            } elseif ($standardFailCount > 0) {
                $status = ChapterModel::TRANS_STATUS_FAILED;
            }
            $attrs['transcode']['standard']['status'] = $status;
        }

        $encryptStatus = $attrs['transcode']['encrypt']['status'] ?? null;

        if ($encryptStatus == ChapterModel::TRANS_STATUS_PROCESSING) {
            if ($encryptSuccessCount > 0) {
                $status = ChapterModel::TRANS_STATUS_FINISHED;
            } elseif ($encryptFailCount > 0) {
                $status = ChapterModel::TRANS_STATUS_FAILED;
            }
            $attrs['transcode']['encrypt']['status'] = $status;
        }

        $chapter->attrs = $attrs;

        $chapter->update();

        $this->pullMediaInfo($chapter->id);

        $this->updateCourseVodAttrs($chapter->course_id);

        return true;
    }

    protected function handleFileDeletedEvent(EventContent $event): bool
    {
        return true;
    }

    /**
     * @return bool|EventContent[]
     */
    protected function pullEvents()
    {
        $vodService = new VodService();

        return $vodService->pullEvents();
    }

    protected function confirmEvents(array $handles): bool
    {
        $vodService = new VodService();

        return $vodService->confirmEvents($handles);
    }

    protected function updateCourseVodAttrs(int $courseId): void
    {
        $courseStats = new CourseStatService();

        $courseStats->updateAttrs($courseId);
    }

    protected function pullMediaInfo(int $chapterId): void
    {
        $chapterRepo = new ChapterRepo();

        $chapterVod = $chapterRepo->findChapterVod($chapterId);

        $service = new ChapterVodService();

        $service->pullMediaInfo($chapterVod);
    }

    protected function getFileDuration(string $fileId): int
    {
        $service = new ChapterVodService();

        $mediaInfo = $service->parseMediaInfo($fileId, 'file_origin');

        return $mediaInfo['duration'] ?? 0;
    }

}
