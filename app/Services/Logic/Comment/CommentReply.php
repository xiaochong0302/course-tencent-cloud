<?php

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\Notice\System\CommentReplied as CommentRepliedNotice;
use App\Services\Logic\Point\History\CommentPost as CommentPostPointHistory;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;
use App\Validators\Comment as CommentValidator;
use App\Validators\UserLimit as UserLimitValidator;

class CommentReply extends LogicService
{

    use ClientTrait;
    use CommentTrait;
    use CommentCountTrait;

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

        $parent = $this->checkComment($reply->parent_id);

        $this->incrUserDailyCommentCount($user);

        $this->incrCommentReplyCount($parent);

        $this->incrItemCommentCount($reply);

        $this->handlePostNotice($reply);

        $this->handlePostPoint($reply);

        $this->eventsManager->fire('Comment:afterReply', $this, $reply);

        return $reply;
    }

    protected function handlePostNotice(CommentModel $comment)
    {
        $notice = new CommentRepliedNotice();

        $notice->handle($comment);
    }

    protected function handlePostPoint(CommentModel $comment)
    {
        if ($comment->published != CommentModel::PUBLISH_APPROVED) return;

        $service = new CommentPostPointHistory();

        $service->handle($comment);
    }

}
