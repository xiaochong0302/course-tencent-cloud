<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\Chapter as ChapterModel;
use App\Repos\Chapter as ChapterRepo;
use App\Services\CourseStat as CourseStatService;
use App\Services\Vod as VodService;

class VodEventTask extends Task
{

    public function mainAction()
    {
        $events = $this->pullEvents();

        if (!$events) return;

        $handles = [];

        foreach ($events as $event) {

            $handles[] = $event['EventHandle'];

            if ($event['EventType'] == 'NewFileUpload') {
                $this->handleNewFileUploadEvent($event);
            } elseif ($event['EventType'] == 'ProcedureStateChanged') {
                $this->handleProcedureStateChangedEvent($event);
            } elseif ($event['EventType'] == 'FileDeleted') {
                $this->handleFileDeletedEvent($event);
            }
        }

        $this->confirmEvents($handles);
    }

    protected function handleNewFileUploadEvent($event)
    {
        $fileId = $event['FileUploadEvent']['FileId'] ?? 0;
        $width = $event['FileUploadEvent']['MetaData']['Height'] ?? 0;
        $height = $event['FileUploadEvent']['MetaData']['Width'] ?? 0;
        $duration = $event['FileUploadEvent']['MetaData']['Duration'] ?? 0;

        if ($fileId == 0) return;

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findByFileId($fileId);

        if (!$chapter) return;

        $attrs = $chapter->attrs;

        /**
         * 获取不到时长，尝试通过接口获得
         */
        if ($duration == 0) {
            $duration = $this->getFileDuration($fileId);
        }

        /**
         * 获取不到时长视为失败
         */
        if ($duration == 0) {
            $attrs['file']['status'] = ChapterModel::FS_FAILED;
            $chapter->update(['attrs' => $attrs]);
            return;
        }

        $vodService = new VodService();

        if ($width == 0 && $height == 0) {
            $vodService->createTransAudioTask($fileId);
        } else {
            $vodService->createTransVideoTask($fileId);
        }

        $attrs['file']['status'] = ChapterModel::FS_TRANSLATING;
        $attrs['duration'] = (int)$duration;

        $chapter->update(['attrs' => $attrs]);

        $this->updateCourseVodAttrs($chapter->course_id);
    }

    protected function handleProcedureStateChangedEvent($event)
    {
        $fileId = $event['ProcedureStateChangeEvent']['FileId'] ?? 0;

        if ($fileId == 0) return;

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findByFileId($fileId);

        if (!$chapter) return;

        $attrs = $chapter->attrs;

        /**
         * 获取不到时长，尝试通过接口获得
         */
        if ($attrs['duration'] == 0) {
            $attrs['duration'] = $this->getFileDuration($fileId);
        }

        $processResult = $event['ProcedureStateChangeEvent']['MediaProcessResultSet'] ?? [];

        /**
         * 获取不到处理结果视为失败
         */
        if (empty($processResult)) {
            $attrs['file']['status'] = ChapterModel::FS_FAILED;
            $chapter->update(['attrs' => $attrs]);
            return;
        }

        $failCount = $successCount = 0;

        foreach ($processResult as $item) {
            if ($item['Type'] == 'Transcode') {
                if ($item['TranscodeTask']['Status'] == 'SUCCESS') {
                    $successCount++;
                } elseif ($item['TranscodeTask']['Status'] == 'FAIL') {
                    $failCount++;
                }
            }
        }

        $fileStatus = ChapterModel::FS_TRANSLATING;

        /**
         * 当有一个成功标记为成功
         */
        if ($successCount > 0) {
            $fileStatus = ChapterModel::FS_TRANSLATED;
        } elseif ($failCount > 0) {
            $fileStatus = ChapterModel::FS_FAILED;
        }

        if ($fileStatus == ChapterModel::FS_TRANSLATING) return;

        $attrs['file']['id'] = $fileId;
        $attrs['file']['status'] = $fileStatus;

        $chapter->update(['attrs' => $attrs]);
    }

    protected function handleFileDeletedEvent($event)
    {

    }

    protected function pullEvents()
    {
        $vodService = new VodService();

        return $vodService->pullEvents();
    }

    protected function confirmEvents($handles)
    {
        $vodService = new VodService();

        return $vodService->confirmEvents($handles);
    }

    protected function updateCourseVodAttrs($courseId)
    {
        $courseStats = new CourseStatService();

        $courseStats->updateVodAttrs($courseId);
    }

    protected function getFileDuration($fileId)
    {
        $service = new VodService();

        $metaInfo = $service->getOriginVideoInfo($fileId);

        return $metaInfo['duration'] ?? 0;
    }

}
