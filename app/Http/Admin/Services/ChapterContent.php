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
use App\Models\ChapterRead as ChapterReadModel;
use App\Models\ChapterVod as ChapterVodModel;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Services\ChapterVod as ChapterVodService;
use App\Services\CourseStat as CourseStatService;
use App\Services\Vod as VodService;
use App\Validators\ChapterRead as ChapterReadValidator;
use App\Validators\ChapterVod as ChapterVodValidator;

class ChapterContent extends Service
{

    public function getChapterVod(int $chapterId): ChapterVodModel
    {
        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findChapterVod($chapterId);
    }

    public function getChapterRead(int $chapterId): ChapterReadModel
    {
        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findChapterRead($chapterId);
    }

    public function updateChapterContent(int $chapterId): void
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($chapterId);

        switch ($chapter->model) {
            case CourseModel::MODEL_VOD:
                $this->updateChapterVod($chapter);
                break;
            case CourseModel::MODEL_READ:
                $this->updateChapterRead($chapter);
                break;
        }

        $this->rebuildCatalogCache($chapter->course_id);
    }

    public function transcode(int $chapterId): void
    {
        $mode = $this->request->getPost('mode');

        $validator = new ChapterVodValidator();

        $mode = $validator->checkTransMode($mode);

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($chapterId);

        $vod = $chapterRepo->findChapterVod($chapterId);

        $vodService = new VodService();

        $attrs = $chapter->attrs;

        if ($mode == ChapterModel::TRANS_MODE_STANDARD) {
            if ($attrs['transcode']['standard']['status'] != ChapterModel::TRANS_STATUS_PROCESSING) {
                $vodService->createTransVideoTask($vod->file_id);
                $attrs['transcode']['standard']['status'] = ChapterModel::TRANS_STATUS_PROCESSING;
            }
        }

        $chapter->assign($attrs);

        $chapter->update();
    }

    protected function updateChapterVod(ChapterModel $chapter): void
    {
        $section = $this->request->getPost('section', 'string', 'settings');

        if ($section == 'cos') {
            $this->updateChapterVodCos($chapter);
        }
    }

    protected function updateChapterVodCos(ChapterModel $chapter): void
    {
        $fileId = $this->request->getPost('file_id', 'string', 0);
        $transMode = $this->request->getPost('trans_mode', 'string', ChapterModel::TRANS_MODE_NONE);

        $chapterRepo = new ChapterRepo();

        $vod = $chapterRepo->findChapterVod($chapter->id);

        $validator = new ChapterVodValidator();

        $fileId = $validator->checkFileId($fileId);
        $transMode = $validator->checkTransMode($transMode);

        $attrs = $chapter->attrs;

        if ($fileId != $vod->file_id) {
            $vod->file_id = $fileId;
            $vod->file_origin = [];
            $vod->file_encrypt = [];
            $vod->file_transcode = [];
            $vod->update();

            $attrs['transcode']['standard']['status'] = ChapterModel::TRANS_STATUS_PENDING;
            $attrs['transcode']['encrypt']['status'] = ChapterModel::TRANS_STATUS_PENDING;
            $attrs['duration'] = 0;
        }

        $vodService = new VodService();

        if ($transMode == ChapterModel::TRANS_MODE_STANDARD) {
            $vodService->createTransVideoTask($vod->file_id);
            $attrs['transcode']['standard']['status'] = ChapterModel::TRANS_STATUS_CREATED;
        }

        $chapter->attrs = $attrs;

        $chapter->update();

        $this->pullMediaInfo($vod->chapter_id);

        $this->updateCourseAttrs($vod->course_id);
    }

    protected function updateChapterRead(ChapterModel $chapter): void
    {
        $post = $this->request->getPost();

        $chapterRepo = new ChapterRepo();

        $read = $chapterRepo->findChapterRead($chapter->id);

        $validator = new ChapterReadValidator();

        $attrs = $chapter->attrs;

        if (isset($post['content'])) {
            $read->content = $validator->checkContent($post['content']);
        }

        $settings = $read->settings;

        if (isset($post['settings'])) {
            $settings['comment_enabled'] = $post['settings']['comment_enabled'] ?? 1;
            $read->settings = $settings;
        }

        $read->update();

        $attrs['word_count'] = WordUtil::getWordCount($read->content);
        $attrs['duration'] = WordUtil::getWordDuration($read->content);
        $chapter->attrs = $attrs;

        $chapter->update();

        $this->updateCourseAttrs($read->course_id);
    }

    protected function updateCourseAttrs(int $courseId): void
    {
        $statService = new CourseStatService();

        $statService->updateAttrs($courseId);
    }

    protected function rebuildCatalogCache(int $courseId): void
    {
        $cache = new CatalogCache();

        $cache->rebuild($courseId);
    }

    protected function pullMediaInfo(int $chapterId): void
    {
        $chapterRepo = new ChapterRepo();

        $chapterVod = $chapterRepo->findChapterVod($chapterId);

        $service = new ChapterVodService();

        $service->pullMediaInfo($chapterVod);
    }

}
