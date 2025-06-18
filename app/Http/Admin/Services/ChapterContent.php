<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Caches\CourseChapterList as CatalogCache;
use App\Library\Utils\Word as WordUtil;
use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Services\ChapterVod as ChapterVodService;
use App\Services\CourseStat as CourseStatService;
use App\Validators\ChapterLive as ChapterLiveValidator;
use App\Validators\ChapterOffline as ChapterOfflineValidator;
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

    public function getChapterOffline($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findChapterOffline($chapterId);
    }

    public function getCosPlayUrls($chapterId)
    {
        $service = new ChapterVodService();

        return $service->getCosPlayUrls($chapterId);
    }

    public function getRemotePlayUrls($chapterId)
    {
        $service = new ChapterVodService();

        return $service->getRemotePlayUrls($chapterId);
    }

    public function getRemoteDuration($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($chapterId);

        $duration = $chapter->attrs['duration'] ?? 0;

        $result = ['hours' => 0, 'minutes' => 0, 'seconds' => 0];

        if ($duration == 0) return $result;

        $result['hours'] = floor($duration / 3600);
        $result['minutes'] = floor(($duration - $result['hours'] * 3600) / 60);
        $result['seconds'] = $duration % 60;

        return $result;
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
            case CourseModel::MODEL_OFFLINE:
                $this->updateChapterOffline($chapter);
                break;
        }

        $this->rebuildCatalogCache($chapter);
    }

    protected function updateChapterVod(ChapterModel $chapter)
    {
        $post = $this->request->getPost();

        if (isset($post['file_id'])) {
            $this->updateCosChapterVod($chapter);
        } elseif (isset($post['file_remote'])) {
            $this->updateRemoteChapterVod($chapter);
        }
    }

    protected function updateCosChapterVod(ChapterModel $chapter)
    {
        $post = $this->request->getPost();

        $validator = new ChapterVodValidator();

        $fileId = $validator->checkFileId($post['file_id']);

        $chapterRepo = new ChapterRepo();

        $vod = $chapterRepo->findChapterVod($chapter->id);

        $attrs = $chapter->attrs;

        if ($fileId != $vod->file_id) {
            $vod->file_id = $fileId;
            $vod->file_transcode = [];
            $vod->update();

            $attrs['file']['status'] = ChapterModel::FS_UPLOADED;
            $attrs['duration'] = 0;
        }

        $chapter->attrs = $attrs;

        $chapter->update();

        $this->updateCourseVodAttrs($vod->course_id);
    }

    protected function updateRemoteChapterVod(ChapterModel $chapter)
    {
        $post = $this->request->getPost();

        $validator = new ChapterVodValidator();

        $hours = $post['file_remote']['duration']['hours'] ?? 0;
        $minutes = $post['file_remote']['duration']['minutes'] ?? 0;
        $seconds = $post['file_remote']['duration']['seconds'] ?? 0;

        $duration = 3600 * $hours + 60 * $minutes + $seconds;

        $validator->checkDuration($duration);

        $hdUrl = $post['file_remote']['hd']['url'] ?? '';
        $sdUrl = $post['file_remote']['sd']['url'] ?? '';
        $fdUrl = $post['file_remote']['fd']['url'] ?? '';

        $fileRemote = [
            'hd' => ['url' => ''],
            'sd' => ['url' => ''],
            'fd' => ['url' => ''],
        ];

        if (!empty($hdUrl)) {
            $fileRemote['hd']['url'] = $validator->checkFileUrl($hdUrl);
        }

        if (!empty($sdUrl)) {
            $fileRemote['sd']['url'] = $validator->checkFileUrl($sdUrl);
        }

        if (!empty($fdUrl)) {
            $fileRemote['fd']['url'] = $validator->checkFileUrl($fdUrl);
        }

        $validator->checkRemoteFile($hdUrl, $sdUrl, $fdUrl);

        $chapterRepo = new ChapterRepo();

        $vod = $chapterRepo->findChapterVod($chapter->id);

        $vod->file_remote = $fileRemote;

        $vod->update();

        $attrs = $chapter->attrs;

        $attrs['file']['status'] = ChapterModel::FS_UPLOADED;
        $attrs['duration'] = $duration;

        $chapter->attrs = $attrs;

        $chapter->update();

        $this->updateCourseVodAttrs($vod->course_id);
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

        $live->start_time = $startTime;
        $live->end_time = $endTime;

        $live->update();

        $attrs = $chapter->attrs;
        $attrs['start_time'] = $startTime;
        $attrs['end_time'] = $endTime;
        $chapter->attrs = $attrs;

        $chapter->update();

        $this->updateCourseLiveAttrs($live->course_id);
    }

    protected function updateChapterRead(ChapterModel $chapter)
    {
        $post = $this->request->getPost();

        $chapterRepo = new ChapterRepo();

        $read = $chapterRepo->findChapterRead($chapter->id);

        $validator = new ChapterReadValidator();

        $content = $validator->checkContent($post['content']);

        $read->content = $content;

        $read->update();

        $attrs = $chapter->attrs;
        $attrs['word_count'] = WordUtil::getWordCount($content);
        $attrs['duration'] = WordUtil::getWordDuration($content);
        $chapter->attrs = $attrs;

        $chapter->update();

        $this->updateCourseReadAttrs($read->course_id);
    }

    protected function updateChapterOffline(ChapterModel $chapter)
    {
        $post = $this->request->getPost();

        $chapterRepo = new ChapterRepo();

        $offline = $chapterRepo->findChapterOffline($chapter->id);

        $validator = new ChapterOfflineValidator();

        $startTime = $validator->checkStartTime($post['start_time']);
        $endTime = $validator->checkEndTime($post['end_time']);

        $validator->checkTimeRange($startTime, $endTime);

        $offline->start_time = $startTime;
        $offline->end_time = $endTime;

        $offline->update();

        $attrs = $chapter->attrs;
        $attrs['start_time'] = $startTime;
        $attrs['end_time'] = $endTime;
        $chapter->attrs = $attrs;

        $chapter->update();

        $this->updateCourseOfflineAttrs($offline->course_id);
    }

    protected function updateCourseVodAttrs($courseId)
    {
        $statService = new CourseStatService();

        $statService->updateVodAttrs($courseId);
    }

    protected function updateCourseLiveAttrs($courseId)
    {
        $statService = new CourseStatService();

        $statService->updateLiveAttrs($courseId);
    }

    protected function updateCourseReadAttrs($courseId)
    {
        $statService = new CourseStatService();

        $statService->updateReadAttrs($courseId);
    }

    protected function updateCourseOfflineAttrs($courseId)
    {
        $statService = new CourseStatService();

        $statService->updateOfflineAttrs($courseId);
    }

    protected function rebuildCatalogCache(ChapterModel $chapter)
    {
        $cache = new CatalogCache();

        $cache->rebuild($chapter->course_id);
    }

}
