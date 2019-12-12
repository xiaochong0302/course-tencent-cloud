<?php

namespace App\Http\Home\Services;

use App\Transformers\CommentList as CommentListTransformer;
use App\Models\Comment as CommentModel;
use App\Exceptions\BadRequest as BadRequestException;
use App\Validators\Comment as CommentFilter;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Comment as CommentRepo;
use App\Repos\CommentVote as CommentVoteRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;
use App\Library\Paginator\Query as PagerQuery;

class Comment extends Service
{
    
    public function create()
    {
        $user = $this->getLoggedUser();

        $post = $this->request->getPost();

        $filter = new CommentFilter();

        $data = [];

        $data['user_id'] = $user->id;
        $data['chapter_id'] = $filter->checkChapterId($post['chapter_id']);
        $data['content'] = $filter->checkContent($post['content']);

        if (!empty($post['parent_id'])) {
            $data['parent_id'] = $filter->checkParentId($post['parent_id']);
        }

        if (!empty($post['to_user_id'])) {
            $data['to_user_id'] = $filter->checkToUserId($post['to_user_id']);
        }
        
        $chapterRepo = new ChapterRepo();
        
        $chapter = $chapterRepo->findById($data['chapter_id']);
        
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapter->course_id);
        
        $data['course_id'] = $course->id;

        $commentRepo = new CommentRepo();

        $comment = $commentRepo->create($data);

        $chapter->comment_count += 1;

        $chapter->update();

        $course->comment_count += 1;

        $course->update();

        return $comment;
    }
    
    public function getComment($id)
    {
        $comment = $this->findOrFail($id);

        return $this->handleComment($comment);
    }

    public function getReplies($id)
    {
        $comment = $this->findOrFail($id);

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $where = [
            'parent_id' => $comment->id,
            'status' => CommentModel::STATUS_NORMAL,
        ];

        $commentRepo = new CommentRepo();

        $pager = $commentRepo->paginate($where, $sort, $page, $limit);

        $pager = $pager->paginate();

        return $this->handleReplies($pager);
    }

    public function delete($id)
    {
        $comment = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $filter = new CommentFilter();

        $filter->checkOwner($user->id, $comment->user_id);
        
        if ($comment->status == CommentModel::STATUS_DELETED) {
            return;
        }

        $comment->status = CommentModel::STATUS_DELETED;

        $comment->update();
    }

    public function agree($id)
    {
        $comment = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $voteRepo = new CommentVoteRepo();

        $vote = $voteRepo->find($user->id, $comment->id);

        if ($vote) {
            throw new BadRequestException('comment.has_voted');
        }

        $voteRepo->agree($user->id, $comment->id);

        $comment->agree_count += 1;

        $comment->update();
    }

    public function oppose($id)
    {
        $comment = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $voteRepo = new CommentVoteRepo();

        $vote = $voteRepo->find($user->id, $comment->id);

        if ($vote) {
            throw new BadRequestException('comment.has_voted');
        }

        $voteRepo->oppose($user->id, $comment->id);

        $comment->oppose_count += 1;

        $comment->update();
    }

    private function findOrFail($id)
    {
        $repo = new CommentRepo();

        $result = $repo->findOrFail($id);

        return $result;
    }

    private function handleComment($comment)
    {
        $result = $comment->toArray();

        $userRepo = new UserRepo();

        $user = $userRepo->findShallowUser($comment->user_id);

        $result['user'] = $user->toArray();

        return (object) $result;
    }

    private function handleReplies($pager)
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

}
