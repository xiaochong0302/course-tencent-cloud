<?php

namespace App\Http\Admin\Services;

use App\Builders\CommentList as CommentListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Repos\Comment as CommentRepo;
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
        $page = $this->findOrFail($id);

        $page->deleted = 1;

        $page->update();

        return $page;
    }

    public function restoreComment($id)
    {
        $page = $this->findOrFail($id);

        $page->deleted = 0;

        $page->update();

        return $page;
    }

    protected function findOrFail($id)
    {
        $validator = new CommentValidator();

        return $validator->checkComment($id);
    }

    protected function handleComments($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new CommentListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleUsers($pipeA);
            $pipeC = $builder->objects($pipeB);

            $pager->items = $pipeC;
        }

        return $pager;
    }

}
