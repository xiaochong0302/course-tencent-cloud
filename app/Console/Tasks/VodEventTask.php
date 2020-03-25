<?php

namespace App\Console\Tasks;

use App\Models\Chapter as ChapterModel;
use App\Repos\Chapter as ChapterRepo;
use App\Services\CourseStats as CourseStatsService;
use App\Services\Vod as VodService;
use Phalcon\Cli\Task;

class VodEventTask extends Task
{

    public function mainAction()
    {
        $events = $this->pullEvents();

        if (!$events) return;

        $handles = [];

        $count = 0;

        foreach ($events as $event) {

            $handles[] = $event['EventHandle'];

            if ($event['EventType'] == 'NewFileUpload') {
                $this->handleNewFileUploadEvent($event);
            } elseif ($event['EventType'] == 'ProcedureStateChanged') {
                $this->handleProcedureStateChangedEvent($event);
            }

            $count++;

            if ($count >= 12) {
                break;
            }
        }

        $this->confirmEvents($handles);
    }

    protected function handleNewFileUploadEvent($event)
    {
        $fileId = $event['FileUploadEvent']['FileId'];
        $format = $event['FileUploadEvent']['MediaBasicInfo']['Type'];
        $duration = $event['FileUploadEvent']['MetaData']['Duration'];

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findByFileId($fileId);

        if (!$chapter) return;

        $vodService = new VodService();

        if ($this->isAudioFile($format)) {
            $vodService->createTransAudioTask($fileId);
        } else {
            $vodService->createTransVideoTask($fileId);
        }

        /**
         * @var array $attrs
         */
        $attrs = $chapter->attrs;

        $attrs['file_status'] = ChapterModel::FS_TRANSLATING;
        $attrs['duration'] = (int)$duration;

        $chapter->update(['attrs' => $attrs]);

        $this->updateVodAttrs($chapter->course_id);
    }

    protected function handleProcedureStateChangedEvent($event)
    {
        $fileId = $event['ProcedureStateChangeEvent']['FileId'];

        $processResult = $event['ProcedureStateChangeEvent']['MediaProcessResultSet'];

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findByFileId($fileId);

        if (!$chapter) return;

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

        if ($fileStatus == ChapterModel::FS_TRANSLATING) {
            return;
        }

        /**
         * @var array $attrs
         */
        $attrs = $chapter->attrs;

        $attrs['file_status'] = $fileStatus;

        $chapter->update(['attrs' => $attrs]);
    }

    protected function pullEvents()
    {
        $vodService = new VodService();

        $result = $vodService->pullEvents();

        return $result;
    }

    protected function confirmEvents($handles)
    {
        $vodService = new VodService();

        $result = $vodService->confirmEvents($handles);

        return $result;
    }

    protected function isAudioFile($format)
    {
        $formats = ['mp3', 'm4a', 'wav', 'flac', 'ogg'];

        $result = in_array(strtolower($format), $formats);

        return $result;
    }

    protected function updateVodAttrs($courseId)
    {
        $courseStats = new CourseStatsService();

        $courseStats->updateVodAttrs($courseId);
    }

}
