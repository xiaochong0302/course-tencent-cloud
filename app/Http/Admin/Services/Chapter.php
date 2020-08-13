<?php

namespace App\Http\Admin\Services;

use App\Caches\Chapter as ChapterCache;
use App\Caches\CourseCatalog as CourseCatalogCache;
use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Models\ChapterRead as ChapterReadModel;
use App\Models\ChapterVod as ChapterVodModel;
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
        $data['summary'] = $validator->checkSummary($post['summary']);

        $chapterRepo = new ChapterRepo();

        $parentId = 0;

        if (isset($post['parent_id'])) {
            $parent = $validator->checkParent($post['parent_id']);
            $data['parent_id'] = $parent->id;
            $data['free'] = $validator->checkFreeStatus($post['free']);
            $data['priority'] = $chapterRepo->maxLessonPriority($post['parent_id']);
        } else {
            $data['priority'] = $chapterRepo->maxChapterPriority($post['course_id']);
            $data['parent_id'] = $parentId;
        }

        $data['priority'] += 1;

        try {

            $this->db->begin();

            $chapter = new ChapterModel();

            if ($chapter->create($data) === false) {
                throw new \RuntimeException('Create Chapter Failed');
            }

            $data = [
                'course_id' => $course->id,
                'chapter_id' => $chapter->id,
            ];

            if ($parentId > 0) {

                $attrs = false;

                switch ($course->model) {
                    case CourseMOdel::MODEL_VOD:
                        $chapterVod = new ChapterVodModel();
                        $attrs = $chapterVod->create($data);
                        break;
                    case CourseModel::MODEL_LIVE:
                        $chapterLive = new ChapterLiveModel();
                        $attrs = $chapterLive->create($data);
                        break;
                    case CourseModel::MODEL_READ:
                        $chapterRead = new ChapterReadModel();
                        $attrs = $chapterRead->create($data);
                        break;
                }

                if ($attrs === false) {
                    throw new \RuntimeException("Create Chapter {$course->model} Attrs Failed");
                }
            }

            $this->db->commit();

            $this->updateChapterStats($chapter);

            $this->updateCourseStat($chapter);

            return $chapter;

        } catch (\Exception $e) {

            $this->db->rollback();

            $logger = $this->getLogger();

            $logger->error('Create Chapter Error ' . kg_json_encode([
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
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
            if ($chapter->published == 0 && $post['published'] == 1) {
                $validator->checkPublishAbility($chapter);
            }
        }

        $chapter->update($data);

        $this->updateChapterStats($chapter);

        $this->updateCourseStat($chapter);

        $this->rebuildCatalogCache($chapter);

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

        return $chapter;
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
        }
    }

    protected function rebuildChapterCache(ChapterModel $chapter)
    {
        $cache = new ChapterCache();

        $cache->rebuild($chapter->id);
    }

    protected function rebuildCatalogCache(ChapterModel $chapter)
    {
        $cache = new CourseCatalogCache();

        $cache->rebuild($chapter->course_id);
    }

    protected function findOrFail($id)
    {
        $validator = new ChapterValidator();

        return $validator->checkChapter($id);
    }

}
