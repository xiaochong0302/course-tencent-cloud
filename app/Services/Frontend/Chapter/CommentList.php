<?php

namespace App\Services\Frontend\Chapter;

use App\Builders\CommentList as CommentListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Comment as CommentRepo;
use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\Service as FrontendService;

class CommentList extends FrontendService
{

    use ChapterTrait;

    public function handle($chapterId)
    {
        $chapter = $this->checkChapter($chapterId);

        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['chapter_id'] = $chapter->id;
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

        $items = [];

        foreach ($comments as $comment) {

            $user = $users[$comment['user_id']] ?? new \stdClass();

            $comment['mentions'] = $comment['mentions'] ? json_decode($comment['mentions']) : [];

            $items[] = [
                'id' => $comment['id'],
                'content' => $comment['content'],
                'mentions' => $comment['mentions'],
                'like_count' => $comment['like_count'],
                'reply_count' => $comment['reply_count'],
                'create_time' => $comment['create_time'],
                'user' => $user,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
