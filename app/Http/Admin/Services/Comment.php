<?php

namespace App\Http\Admin\Services;

use App\Builders\CommentList as CommentListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Comment as CommentRepo;
use App\Repos\Course as CourseRepo;
use App\Validators\Comment as CommentValidator;

class Comment extends Service
{

    public function getComments()
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $commentRepo = new CommentRepo();

        $pager = $commentRepo->paginate($params, $sort, $page, $limit);

        return $this->handleComments($pager);
    }

    public function getCourse($courseId)
    {
        $courseRepo = new CourseRepo();

        return $courseRepo->findById($courseId);
    }

    public function getChapter($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findById($chapterId);
    }

    public function getComment($id)
    {
        return $this->findOrFail($id);
    }

    public function updateComment($id)
    {
        $comment = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new CommentValidator();

        $data = [];

        if (isset($post['content'])) {
            $data['content'] = $validator->checkContent($post['content']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        $comment->update($data);

        return $comment;
    }

    public function deleteComment($id)
    {
        $comment = $this->findOrFail($id);

        $comment->deleted = 1;

        $comment->update();

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($comment->chapter_id);

        $chapter->comment_count -= 1;

        $chapter->update();

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($comment->course_id);

        $course->comment_count -= 1;

        $course->update();
    }

    public function restoreComment($id)
    {
        $comment = $this->findOrFail($id);

        $comment->deleted = 0;

        $comment->update();

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($comment->chapter_id);

        $chapter->comment_count += 1;

        $chapter->update();

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($comment->course_id);

        $course->comment_count += 1;

        $course->update();
    }

    private function findOrFail($id)
    {
        $validator = new CommentValidator();

        return $validator->checkComment($id);
    }

    private function handleComments($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new CommentListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleCourses($pipeA);
            $pipeC = $builder->handleChapters($pipeB);
            $pipeD = $builder->handleUsers($pipeC);
            $pipeE = $builder->arrayToObject($pipeD);

            $pager->items = $pipeE;
        }

        return $pager;
    }

}
