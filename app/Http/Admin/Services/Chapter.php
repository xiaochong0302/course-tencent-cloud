<?php

namespace App\Http\Admin\Services;

use App\Caches\Chapter as ChapterCache;
use App\Caches\CourseChapterList as CourseChapterListCache;
use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Services\CourseStats as CourseStatsService;
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

        if (isset($post['parent_id'])) {
            $parent = $validator->checkParent($post['parent_id']);
            $data['parent_id'] = $parent->id;
            $data['free'] = $validator->checkFreeStatus($post['free']);
            $data['priority'] = $chapterRepo->maxLessonPriority($post['parent_id']);
        } else {
            $data['priority'] = $chapterRepo->maxChapterPriority($post['course_id']);
        }

        $data['priority'] += 1;

        $chapter = new ChapterModel();

        $chapter->create($data);

        $this->updateChapterStats($chapter);

        $this->updateCourseStats($chapter);

        $this->rebuildChapterCache($chapter);

        return $chapter;
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

        $this->updateCourseStats($chapter);

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

        $this->updateCourseStats($chapter);

        $this->rebuildChapterCache($chapter);

        return $chapter;
    }

    public function restoreChapter($id)
    {
        $chapter = $this->findOrFail($id);

        $chapter->deleted = 0;

        $chapter->update();

        $this->updateChapterStats($chapter);

        $this->updateCourseStats($chapter);

        $this->rebuildChapterCache($chapter);

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

    protected function updateCourseStats(ChapterModel $chapter)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapter->course_id);

        $courseStats = new CourseStatsService();

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

        $cache = new CourseChapterListCache();

        $cache->rebuild($chapter->course_id);
    }

    protected function findOrFail($id)
    {
        $validator = new ChapterValidator();

        return $validator->checkChapter($id);
    }

}
