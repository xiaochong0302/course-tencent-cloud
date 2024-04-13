<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Caches\Chapter as ChapterCache;
use App\Caches\CourseChapterList as CatalogCache;
use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Services\CourseStat as CourseStatService;
use App\Validators\Chapter as ChapterValidator;

class Chapter extends Service
{

    public function getLessons($parentId)
    {
        $deleted = $this->request->getQuery('deleted', 'int', 0);

        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findAll([
            'parent_id' => $parentId,
            'deleted' => $deleted,
        ]);
    }

    public function getChapter($id)
    {
        return $this->findOrFail($id);
    }

    public function createChapter()
    {
        $post = $this->request->getPost();

        $validator = new ChapterValidator();

        $data = [];

        $course = $validator->checkCourse($post['course_id']);

        $data['course_id'] = $course->id;
        $data['title'] = $validator->checkTitle($post['title']);

        $chapterRepo = new ChapterRepo();

        if (isset($post['parent_id'])) {
            $parent = $validator->checkParent($post['parent_id']);
            $data['parent_id'] = $parent->id;
            $data['priority'] = $chapterRepo->maxLessonPriority($post['parent_id']);
        } else {
            $data['priority'] = $chapterRepo->maxChapterPriority($post['course_id']);
            $data['parent_id'] = 0;
        }

        /**
         * 排序从10开始递增，步长为5
         */
        if ($data['priority'] < 10) {
            $data['priority'] = 10;
        } else {
            $data['priority'] += 5;
        }

        try {

            $this->db->begin();

            $chapter = new ChapterModel();

            if ($chapter->create($data) === false) {
                throw new \RuntimeException('Create Chapter Failed');
            }

            $this->db->commit();

            $this->updateChapterStats($chapter);
            $this->updateCourseStat($chapter);
            $this->rebuildCatalogCache($chapter);
            $this->rebuildChapterCache($chapter);

            return $chapter;

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger = $this->getLogger('http');

            $logger->error('Create Chapter Error ' . kg_json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                ]));

            throw new \RuntimeException('sys.trans_rollback');
        }
    }

    public function updateChapter($id)
    {
        $chapter = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new ChapterValidator();

        $data = [];

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['summary'])) {
            $data['summary'] = $validator->checkSummary($post['summary']);
        }

        if (isset($post['priority'])) {
            $data['priority'] = $validator->checkPriority($post['priority']);
        }

        if (isset($post['free'])) {
            $data['free'] = $validator->checkFreeStatus($post['free']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
            if ($post['published'] == 1) {
                $validator->checkPublishAbility($chapter);
            }
        }

        $chapter->update($data);

        $this->updateChapterStats($chapter);
        $this->updateCourseStat($chapter);
        $this->rebuildCatalogCache($chapter);
        $this->rebuildChapterCache($chapter);

        return $chapter;
    }

    public function deleteChapter($id)
    {
        $chapter = $this->findOrFail($id);

        $validator = new ChapterValidator();

        $validator->checkDeleteAbility($chapter);

        $chapter->deleted = 1;

        $chapter->update();

        $this->updateChapterStats($chapter);
        $this->updateCourseStat($chapter);
        $this->rebuildCatalogCache($chapter);
        $this->rebuildChapterCache($chapter);

        return $chapter;
    }

    public function restoreChapter($id)
    {
        $chapter = $this->findOrFail($id);

        $chapter->deleted = 0;

        $chapter->update();

        $this->updateChapterStats($chapter);
        $this->updateCourseStat($chapter);
        $this->rebuildCatalogCache($chapter);
        $this->rebuildChapterCache($chapter);

        return $chapter;
    }

    protected function findOrFail($id)
    {
        $validator = new ChapterValidator();

        return $validator->checkChapter($id);
    }

    protected function updateChapterStats(ChapterModel $chapter)
    {
        $chapterRepo = new ChapterRepo();

        if ($chapter->parent_id > 0) {
            $chapter = $chapterRepo->findById($chapter->parent_id);
        }

        $lessonCount = $chapterRepo->countLessons($chapter->id);

        $chapter->lesson_count = $lessonCount;

        $chapter->update();
    }

    protected function updateCourseStat(ChapterModel $chapter)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapter->course_id);

        $courseStats = new CourseStatService();

        $courseStats->updateLessonCount($course->id);

        if ($course->model == CourseModel::MODEL_VOD) {
            $courseStats->updateVodAttrs($course->id);
        } elseif ($course->model == CourseModel::MODEL_LIVE) {
            $courseStats->updateLiveAttrs($course->id);
        } elseif ($course->model == CourseModel::MODEL_READ) {
            $courseStats->updateReadAttrs($course->id);
        } elseif ($course->model == CourseModel::MODEL_OFFLINE) {
            $courseStats->updateOfflineAttrs($course->id);
        }
    }

    protected function rebuildChapterCache(ChapterModel $chapter)
    {
        $cache = new ChapterCache();

        $cache->rebuild($chapter->id);
    }

    protected function rebuildCatalogCache(ChapterModel $chapter)
    {
        $cache = new CatalogCache();

        $cache->rebuild($chapter->course_id);
    }

}
