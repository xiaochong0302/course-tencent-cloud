<?php

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

        $attrs['file']['status'] = ChapterModel::FS_TRANSLATING;

        $attrs['duration'] = (int)$duration;

        $chapter->update(['attrs' => $attrs]);

        $this->updateVodAttrs($chapter);
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

        $attrs['file']['status'] = $fileStatus;

        $chapter->update(['attrs' => $attrs]);
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

    protected function isAudioFile($format)
    {
        $formats = ['mp3', 'm4a', 'wav', 'flac', 'ogg'];

        return in_array(strtolower($format), $formats);
    }

    protected function updateVodAttrs(ChapterModel $chapter)
    {
        $courseStats = new CourseStatService();

        $courseStats->updateVodAttrs($chapter->course_id);
    }

}
