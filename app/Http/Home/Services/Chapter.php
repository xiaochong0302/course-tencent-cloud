<?php

namespace App\Http\Home\Services;

use App\Transformers\CommentList as CommentListTransformer;
use App\Models\Comment as CommentModel;
use App\Exceptions\BadRequest as BadRequestException;
use App\Validators\Chapter as ChapterFilter;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\ChapterVote as ChapterVoteRepo;
use App\Repos\ChapterUser as ChapterUserRepo;
use App\Repos\Comment as CommentRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\Video as VideoRepo;
use App\Library\Paginator\Query as PagerQuery;

class Chapter extends Service
{

    public function getChapter($id)
    {
        $chapter = $this->findOrFail($id);

        $user = $this->getCurrentUser();

        return $this->handleChapter($user, $chapter);
    }

    public function getComments($id)
    {
        $chapter = $this->findOrFail($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $where = [
            'chapter_id' => $chapter->id,
            'status' => CommentModel::STATUS_NORMAL,
        ];

        $commentRepo = new CommentRepo();

        $pager = $commentRepo->paginate($where, $sort, $page, $limit);

        return $this->handleComments($pager);
    }

    public function agree($id)
    {
        $chapter = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $voteRepo = new ChapterVoteRepo();

        $vote = $voteRepo->find($user->id, $chapter->id);

        if ($vote) {
            throw new BadRequestException('chapter.has_voted');
        }

        $voteRepo->agree($user->id, $chapter->id);

        $chapter->agree_count += 1;

        $chapter->update();
    }

    public function oppose($id)
    {
        $chapter = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $chapterVoteRepo = new ChapterVoteRepo();

        $vote = $chapterVoteRepo->find($user->id, $chapter->id);

        if ($vote) {
            throw new BadRequestException('chapter.has_voted');
        }

        $chapterVoteRepo->oppose($user->id, $chapter->id);

        $chapter->oppose_count += 1;

        $chapter->update();
    }

    public function position($id)
    {
        $chapter = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $chapterUserRepo = new ChapterUserRepo();

        $chapterUser = $chapterUserRepo->find($user->id, $chapter->id);

        if (!$chapterUser) {
            return;
        }

        $filter = new ChapterFilter();

        $position = $this->request->getPost('position');

        $chapterUser->position = $filter->checkPosition($position, $chapter->duration);

        $chapterUser->update();
    }

    public function finish($id)
    {
        $chapter = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $chapterUserRepo = new ChapterUserRepo();

        $chapterUser = $chapterUserRepo->find($user->id, $chapter->id);

        if (!$chapterUser) {
            throw new BadRequestException('chapter.has_not_started');
        }

        if ($chapterUser->finished == 1) {
            return;
        }

        $chapterUser->finished = 1;

        $chapterUser->update();

        $chapter->finish_count += 1;

        $chapter->update();
    }

    private function findOrFail($id)
    {
        $repo = new ChapterRepo();

        $result = $repo->findOrFail($id);

        return $result;
    }

    private function handleComments($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new CommentListTransformer();

            $pipeA = $pager->items->toArray();

            $pipeB = $builder->handleUsers($pipeA);

            $pipeC = $builder->arrayToObject($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

    private function handleChapter($user, $chapter)
    {
        $result = $chapter->toArray();

        $result['me']['allow_view'] = 0;

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapter->course_id);

        $filter = new ChapterFilter();

        $allowView = $filter->checkViewPriv($user, $chapter, $course);

        if ($allowView) {

            $result['me']['allow_view'] = 1;

            $chapterUserRepo = new ChapterUserRepo();

            $chapterUser = $chapterUserRepo->find($user->id, $chapter->id);

            if (!$chapterUser) {
                $chapterUserRepo->create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'chapter_id' => $chapter->id,
                ]);
            }

            $videoRepo = new VideoRepo();

            $videos = $videoRepo->findByChapterId($chapter->id);

            $result['videos'] = $videos->toArray();
        }

        return $result;
    }

}
