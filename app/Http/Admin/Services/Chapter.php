<?php

namespace App\Http\Admin\Services;

use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Services\CourseStats as CourseStatsService;
use App\Validators\Chapter as ChapterValidator;

class Chapter extends Service
{

    public function getCourse($courseId)
    {
        $courseRepo = new CourseRepo();

        $result = $courseRepo->findById($courseId);

        return $result;
    }

    public function getCourseChapters($courseId)
    {
        $chapterRepo = new ChapterRepo();

        $result = $chapterRepo->findAll([
            'course_id' => $courseId,
            'parent_id' => 0,
            'deleted' => 0,
        ]);

        return $result;
    }

    public function getLessons($parentId)
    {
        $deleted = $this->request->getQuery('deleted', 'int', 0);

        $chapterRepo = new ChapterRepo();

        $result = $chapterRepo->findAll([
            'parent_id' => $parentId,
            'deleted' => $deleted,
        ]);

        return $result;
    }

    public function getChapter($id)
    {
        $chapter = $this->findOrFail($id);

        return $chapter;
    }

    public function createChapter()
    {
        $post = $this->request->getPost();

        $validator = new ChapterValidator();

        $data = [];

        $data['course_id'] = $validator->checkCourseId($post['course_id']);
        $data['title'] = $validator->checkTitle($post['title']);
        $data['summary'] = $validator->checkSummary($post['summary']);
        $data['free'] = $validator->checkFreeStatus($post['free']);

        $chapterRepo = new ChapterRepo();

        if (isset($post['parent_id'])) {
            $data['parent_id'] = $validator->checkParentId($post['parent_id']);
            $data['priority'] = $chapterRepo->maxLessonPriority($post['parent_id']);
        } else {
            $data['priority'] = $chapterRepo->maxChapterPriority($post['course_id']);
        }

        $data['priority'] += 1;

        $chapter = new ChapterModel();

        $chapter->create($data);

        $this->updateChapterStats($chapter);
        $this->updateCourseStats($chapter);

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
            if ($post['published'] == 1) {
                $validator->checkPublishAbility($chapter);
            }
        }

        $chapter->update($data);

        $this->updateChapterStats($chapter);
        $this->updateCourseStats($chapter);

        return $chapter;
    }

    public function deleteChapter($id)
    {
        $chapter = $this->findOrFail($id);

        if ($chapter->deleted == 1) {
            return false;
        }

        $chapter->deleted = 1;

        $chapter->update();

        if ($chapter->parent_id == 0) {
            $this->deleteChildChapters($chapter->id);
        }

        return $chapter;
    }

    public function restoreChapter($id)
    {
        $chapter = $this->findOrFail($id);

        if ($chapter->deleted == 0) {
            return false;
        }

        $chapter->deleted = 0;

        $chapter->update();

        if ($chapter->parent_id == 0) {
            $this->restoreChildChapters($chapter->id);
        }

        $this->updateChapterStats($chapter);
        $this->updateCourseStats($chapter);

        return $chapter;
    }

    protected function deleteChildChapters($parentId)
    {
        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findAll(['parent_id' => $parentId]);

        if ($chapters->count() == 0) {
            return;
        }

        foreach ($chapters as $chapter) {
            $chapter->deleted = 1;
            $chapter->update();
        }
    }

    protected function restoreChildChapters($parentId)
    {
        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findAll(['parent_id' => $parentId]);

        if ($chapters->count() == 0) {
            return;
        }

        foreach ($chapters as $chapter) {
            $chapter->deleted = 0;
            $chapter->update();
        }
    }

    protected function updateChapterStats($chapter)
    {
        $chapterRepo = new ChapterRepo();

        if ($chapter->parent_id > 0) {
            $chapter = $chapterRepo->findById($chapter->parent_id);
        }

        $lessonCount = $chapterRepo->countLessons($chapter->id);
        $chapter->lesson_count = $lessonCount;
        $chapter->update();

    }

    protected function updateCourseStats($chapter)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapter->course_id);

        $courseStats = new CourseStatsService();

        $courseStats->updateLessonCount($course->id);

        if ($course->model == CourseModel::MODEL_VOD) {
            $courseStats->updateVodDuration($course->id);
        } elseif ($course->model == CourseModel::MODEL_LIVE) {
            $courseStats->updateLiveDateRange($course->id);
        } elseif ($course->model == CourseModel::MODEL_ARTICLE) {
            $courseStats->updateArticleWordCount($course->id);
        }
    }

    protected function findOrFail($id)
    {
        $validator = new ChapterValidator();

        $result = $validator->checkChapter($id);

        return $result;
    }

}
