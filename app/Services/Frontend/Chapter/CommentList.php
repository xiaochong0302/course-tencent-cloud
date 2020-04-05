<?php

namespace App\Services\Frontend\Chapter;

use App\Builders\CommentList as CommentListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\Chapter as ChapterModel;
use App\Models\CommentVote as CommentVoteModel;
use App\Models\User as UserModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Comment as CommentRepo;
use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\Service;

class CommentList extends Service
{

    /**
     * @var ChapterModel
     */
    protected $chapter;

    /**
     * @var UserModel
     */
    protected $user;

    use ChapterTrait;

    public function getComments($chapterId)
    {
        $this->chapter = $this->checkChapter($chapterId);

        $this->user = $this->getCurrentUser();

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['chapter_id'] = $this->chapter->id;
        $params['published'] = 1;
        $params['deleted'] = 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $commentRepo = new CommentRepo();

        $pager = $commentRepo->paginate($params, $sort, $page, $limit);

        return $this->handleComments($pager);
    }

    protected function handleComments($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $comments = $pager->items->toArray();

        $builder = new CommentListBuilder();

        $users = $builder->getUsers($comments);

        $votes = $this->getCommentVotes($this->chapter->id, $this->user->id);

        $items = [];

        foreach ($comments as $comment) {

            $user = $users[$comment['user_id']] ?? [];

            $comment['mentions'] = $comment['mentions'] ? json_decode($comment['mentions']) : [];

            $me = [
                'agreed' => $votes[$comment['id']]['agreed'] ?? false,
                'opposed' => $votes[$comment['id']]['opposed'] ?? false,
            ];

            $items[] = [
                'id' => $comment['id'],
                'content' => $comment['content'],
                'mentions' => $comment['mentions'],
                'agree_count' => $comment['agree_count'],
                'oppose_count' => $comment['oppose_count'],
                'reply_count' => $comment['reply_count'],
                'create_time' => $comment['create_time'],
                'user' => $user,
                'me' => $me,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function getCommentVotes($chapterId, $userId)
    {
        if (!$chapterId || !$userId) {
            return [];
        }

        $chapterRepo = new ChapterRepo();

        $votes = $chapterRepo->findUserCommentVotes($chapterId, $userId);

        if ($votes->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($votes as $vote) {
            $result[$vote->comment_id] = [
                'agreed' => $vote->type == CommentVoteModel::TYPE_AGREE,
                'opposed' => $vote->type == CommentVoteModel::TYPE_OPPOSE,
            ];
        }

        return $result;
    }

}
