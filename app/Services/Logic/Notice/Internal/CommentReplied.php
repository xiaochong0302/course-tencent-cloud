<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Notice\Internal;

use App\Models\Comment as CommentModel;
use App\Models\Notification as NotificationModel;
use App\Repos\Comment as CommentRepo;
use App\Repos\User as UserRepo;
use App\Services\Logic\Service as LogicService;

class CommentReplied extends LogicService
{

    public function handle(CommentModel $reply)
    {
        $replyContent = kg_substr($reply->content, 0, 36);

        $comment = $this->findComment($reply->parent_id);

        $commentContent = kg_substr($comment->content, 0, 32);

        $notification = new NotificationModel();

        $notification->sender_id = $reply->owner_id;
        $notification->receiver_id = $comment->owner_id;
        $notification->event_id = $reply->id;
        $notification->event_type = NotificationModel::TYPE_COMMENT_REPLIED;
        $notification->event_info = [
            'comment' => ['id' => $comment->id, 'content' => $commentContent],
            'reply' => ['id' => $reply->id, 'content' => $replyContent],
        ];

        $notification->create();
    }

    protected function findComment($id)
    {
        $commentRepo = new CommentRepo();

        return $commentRepo->findById($id);
    }

    protected function findUser($id)
    {
        $userRepo = new UserRepo();

        return $userRepo->findById($id);
    }

}
