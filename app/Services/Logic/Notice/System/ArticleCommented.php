<?php

namespace App\Services\Logic\Notice\System;

use App\Models\Comment as CommentModel;
use App\Models\Notification as NotificationModel;
use App\Repos\Article as ArticleRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Service as LogicService;

class ArticleCommented extends LogicService
{

    public function handle(CommentModel $comment)
    {
        $comment->content = kg_substr($comment->content, 0, 32);

        $article = $this->findArticle($comment->item_id);

        $notification = new NotificationModel();

        $notification->sender_id = $comment->owner_id;
        $notification->receiver_id = $article->owner_id;
        $notification->event_id = $comment->id;
        $notification->event_type = NotificationModel::TYPE_ARTICLE_COMMENTED;
        $notification->event_info = [
            'article' => ['id' => $article->id, 'title' => $article->title],
            'comment' => ['id' => $comment->id, 'content' => $comment->content],
        ];

        $notification->create();
    }

    protected function findArticle($id)
    {
        $articleRepo = new ArticleRepo();

        return $articleRepo->findById($id);
    }

    protected function findUser($id)
    {
        $userRepo = new UserRepo();

        return $userRepo->findById($id);
    }

}
