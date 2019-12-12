<?php

namespace App\Http\Home\Services;

use App\Validators\Chapter as ChapterFilter;
use App\Models\Chapter as ChapterModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;

class ChapterAdmin extends Service
{

    public function getChapter($id)
    {
        $chapter = $this->findOrFail($id);

        return $chapter;
    }

    public function getCourse($courseId)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        return $course;
    }

    public function getTopChapters($courseId)
    {
        $courseRepo = new CourseRepo();

        $result = $courseRepo->findTopChapters($courseId);

        return $result;
    }

    public function create()
    {
        $user = $this->getLoggedUser();

        $post = $this->request->getPost();

        $filter = new ChapterFilter();

        $data = [];

        $data['course_id'] = $filter->checkCourseId($post['course_id']);
        $data['title'] = $filter->checkTitle($post['title']);
        $data['summary'] = $filter->checkSummary($post['summary']);
        $data['free'] = $filter->checkFree($post['free']);

        $chapterRepo = new ChapterRepo();

        if (isset($post['parent_id'])) {
            $data['parent_id'] = $filter->checkParentId($post['parent_id']);
            $data['priority'] = $chapterRepo->maxSectionPriority($data['parent_id']);
        } else {
            $data['priority'] = $chapterRepo->maxChapterPriority($data['course_id']);
        }

        $data['priority'] += 1;
        $data['user_id'] = $user->id;
        $data['status'] = ChapterModel::STATUS_DRAFT;

        $chapter = $chapterRepo->create($data);

        if ($chapter->parent_id > 0) {

            $service = new ChapterContent();

            $service->create($chapter);

            $this->updateChapterCount($chapter);
        }

        return $chapter;
    }

    public function update($id)
    {
        $chapter = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $post = $this->request->getPost();

        $filter = new ChapterFilter();

        $filter->checkOwner($user->id, $chapter->user_id);

        $data = [];

        if (isset($post['title'])) {
            $data['title'] = $filter->checkTitle($post['title']);
        }

        if (isset($post['summary'])) {
            $data['summary'] = $filter->checkSummary($post['summary']);
        }

        if (isset($post['free'])) {
            $data['free'] = $filter->checkFree($post['free']);
        }

        if (isset($post['priority'])) {
            $data['priority'] = $filter->checkPriority($post['priority']);
        }

        $chapter->update($data);

        return $chapter;
    }

    public function delete($id)
    {
        $chapter = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $filter = new ChapterFilter();

        $filter->checkOwner($user->id, $chapter->user_id);
        
        if ($chapter->status == ChapterModel::STATUS_DELETED) {
            return;
        }

        $chapter->status = ChapterModel::STATUS_DELETED;

        $chapter->update();

        $this->updateChapterCount($chapter);
    }

    public function getContent($id)
    {
        $service = new ChapterContent();

        $chapter = $this->findOrFail($id);

        $result = $service->get($chapter);

        return $result;
    }

    public function updateContent($id)
    {
        $service = new ChapterContent();

        $chapter = $this->findOrFail($id);

        $result = $service->update($chapter);

        return $result;
    }

    private function findOrFail($id)
    {
        $repo = new ChapterRepo();

        $result = $repo->findOrFail($id);

        return $result;
    }

    private function updateChapterCount($chapter)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapter->course_id);

        $chapterCount = $courseRepo->countChapters($course->id);

        $course->chapter_count = $chapterCount;

        $course->update();
    }

}
