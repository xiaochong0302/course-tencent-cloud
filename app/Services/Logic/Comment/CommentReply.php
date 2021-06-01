<?php

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;
use App\Validators\Comment as CommentValidator;
use App\Validators\UserLimit as UserLimitValidator;

class CommentReply extends LogicService
{

    use AfterCreateTrait;
    use CommentTrait;
    use CountTrait;
    use ClientTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $comment = $this->checkComment($id);

        $validator = new UserLimitValidator();

        $validator->checkDailyCommentLimit($user);

        $parent = $comment;

        $validator = new CommentValidator();

        $data = [
            'parent_id' => $parent->id,
            'item_id' => $comment->item_id,
            'item_type' => $comment->item_type,
            'owner_id' => $user->id,
        ];

        $item = $validator->checkItem($comment->item_id, $comment->item_type);

        /**
         * 子评论中回复用户
         */
        if ($comment->parent_id > 0) {
            $parent = $validator->checkParent($comment->parent_id);
            $data['parent_id'] = $parent->id;
            $data['to_user_id'] = $comment->owner_id;
        }

        $data['content'] = $validator->checkContent($post['content']);
        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

        /**
         * @todo 引入自动审核机制
         */
        $data['published'] = CommentModel::PUBLISH_APPROVED;

        $reply = new CommentModel();

        $reply->create($data);

        $this->incrUserDailyCommentCount($user);

        if ($reply->published == CommentModel::PUBLISH_APPROVED) {
            $this->incrCommentReplyCount($parent);
            $this->handleNoticeAndPoint($item, $reply, $user);
        }

        $this->eventsManager->fire('Comment:afterReply', $this, $reply);

        return $reply;
    }

}
