<?php

namespace App\Http\Admin\Services;

use App\Library\Util\Word as WordUtil;
use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Services\ChapterVod as ChapterVodService;
use App\Services\CourseStats as CourseStatsService;
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
        $chapterVodService = new ChapterVodService();

        $playUrls = $chapterVodService->getPlayUrls($chapterId);

        return kg_array_object($playUrls);
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
        $attrs['file_id'] = $fileId;
        $attrs['file_status'] = ChapterModel::FS_UPLOADED;

        $chapter->update(['attrs' => $attrs]);

        $courseStats = new CourseStatsService();

        $courseStats->updateVodAttrs($chapter->course_id);
    }

    protected function updateChapterLive(ChapterModel $chapter)
    {
        $post = $this->request->getPost();

        $chapterRepo = new ChapterRepo();

        $live = $chapterRepo->findChapterLive($chapter->id);

        $validator = new ChapterLiveValidator();

        $data = [];

        $data['start_time'] = $validator->checkStartTime($post['start_time']);
        $data['end_time'] = $validator->checkEndTime($post['end_time']);

        $validator->checkTimeRange($post['start_time'], $post['end_time']);

        $live->update($data);

        /**
         * @var array $attrs
         */
        $attrs = $chapter->attrs;

        $attrs['start_time'] = $data['start_time'];
        $attrs['end_time'] = $data['end_time'];

        $chapter->update(['attrs' => $attrs]);

        $courseStats = new CourseStatsService();

        $courseStats->updateLiveAttrs($chapter->course_id);
    }

    protected function updateChapterRead(ChapterModel $chapter)
    {
        $post = $this->request->getPost();

        $chapterRepo = new ChapterRepo();

        $read = $chapterRepo->findChapterRead($chapter->id);

        $validator = new ChapterReadValidator();

        $data = [];

        $data['content'] = $validator->checkContent($post['content']);

        $read->update($data);

        /**
         * @var array $attrs
         */
        $attrs = $chapter->attrs;

        $attrs['word_count'] = WordUtil::getWordCount($read->content);
        $attrs['duration'] = WordUtil::getWordDuration($read->content);

        $chapter->update(['attrs' => $attrs]);

        $courseStats = new CourseStatsService();

        $courseStats->updateReadAttrs($chapter->course_id);
    }

}
