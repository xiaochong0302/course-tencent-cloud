<?php

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;
use App\Validators\Comment as CommentValidator;
use App\Validators\UserLimit as UserLimitValidator;

class CommentCreate extends LogicService
{

    use AfterCreateTrait;
    use CountTrait;
    use ClientTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyCommentLimit($user);

        $validator = new CommentValidator();

        $item = $validator->checkItem($post['item_id'], $post['item_type']);

        $comment = new CommentModel();

        $data = [
            'item_id' => $post['item_id'],
            'item_type' => $post['item_type'],
            'owner_id' => $user->id,
        ];

        $data['content'] = $validator->checkContent($post['content']);
        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

        /**
         * @todo 引入自动审核机制
         */
        $data['published'] = CommentModel::PUBLISH_APPROVED;

        $comment->create($data);

        $this->incrUserDailyCommentCount($user);

        if ($comment->published == CommentModel::PUBLISH_APPROVED) {
            $this->handleNoticeAndPoint($item, $comment, $user);
        }

        $this->eventsManager->fire('Comment:afterCreate', $this, $comment);

        return $comment;
    }

}
