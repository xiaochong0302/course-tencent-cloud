<?php

namespace App\Http\Admin\Services;

use App\Library\Utils\Word as WordUtil;
use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Services\ChapterVod as ChapterVodService;
use App\Services\CourseStat as CourseStatService;
use App\Validators\ChapterLive as ChapterLiveValidator;
use App\Validators\ChapterRead as ChapterReadValidator;
use App\Validators\ChapterVod as ChapterVodValidator;

class ChapterContent extends Service
{

    public function getChapterVod($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findChapterVod($chapterId);
    }

    public function getChapterLive($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findChapterLive($chapterId);
    }

    public function getChapterRead($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findChapterRead($chapterId);
    }

    public function getPlayUrls($chapterId)
    {
        $service = new ChapterVodService();

        return $service->getPlayUrls($chapterId);
    }

    public function updateChapterContent($chapterId)
    {
        $chapterRepo = new ChapterRepo();
        $chapter = $chapterRepo->findById($chapterId);

        $courseRepo = new CourseRepo();
        $course = $courseRepo->findById($chapter->course_id);

        switch ($course->model) {
            case CourseModel::MODEL_VOD:
                $this->updateChapterVod($chapter);
                break;
            case CourseModel::MODEL_LIVE:
                $this->updateChapterLive($chapter);
                break;
            case CourseModel::MODEL_READ:
                $this->updateChapterRead($chapter);
                break;
        }
    }

    protected function updateChapterVod(ChapterModel $chapter)
    {
        $post = $this->request->getPost();

        $validator = new ChapterVodValidator();

        $fileId = $validator->checkFileId($post['file_id']);

        $chapterRepo = new ChapterRepo();

        $vod = $chapterRepo->findChapterVod($chapter->id);

        if ($fileId == $vod->file_id) {
            return;
        }

        $vod->update([
            'file_id' => $fileId,
            'file_transcode' => '',
        ]);

        /**
         * @var array $attrs
         */
        $attrs = $chapter->attrs;

        $attrs['duration'] = 0;

        $attrs['file']['status'] = ChapterModel::FS_UPLOADED;

        $chapter->update(['attrs' => $attrs]);

        $courseStats = new CourseStatService();

        $courseStats->updateVodAttrs($chapter->course_id);
    }

    protected function updateChapterLive(ChapterModel $chapter)
    {
        $post = $this->request->getPost();

        $chapterRepo = new ChapterRepo();

        $live = $chapterRepo->findChapterLive($chapter->id);

        $validator = new ChapterLiveValidator();

        $startTime = $validator->checkStartTime($post['start_time']);
        $endTime = $validator->checkEndTime($post['end_time']);

        $validator->checkTimeRange($startTime, $endTime);

        $live->update([
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        /**
         * @var array $attrs
         */
        $attrs = $chapter->attrs;

        $attrs['start_time'] = $startTime;
        $attrs['end_time'] = $endTime;

        $chapter->update(['attrs' => $attrs]);

        $courseStats = new CourseStatService();

        $courseStats->updateLiveAttrs($chapter->course_id);
    }

    protected function updateChapterRead(ChapterModel $chapter)
    {
        $post = $this->request->getPost();

        $chapterRepo = new ChapterRepo();

        $read = $chapterRepo->findChapterRead($chapter->id);

        $validator = new ChapterReadValidator();

        $content = $validator->checkContent($post['content']);

        $read->update(['content' => $content]);

        /**
         * @var array $attrs
         */
        $attrs = $chapter->attrs;

        $attrs['word_count'] = WordUtil::getWordCount($content);
        $attrs['duration'] = WordUtil::getWordDuration($content);

        $chapter->update(['attrs' => $attrs]);

        $courseStats = new CourseStatService();

        $courseStats->updateReadAttrs($chapter->course_id);
    }

}
