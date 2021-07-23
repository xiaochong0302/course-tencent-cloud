<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Comment as CommentValidator;
use App\Validators\UserLimit as UserLimitValidator;

class CommentReply extends LogicService
{

    use AfterCreateTrait;
    use CommentDataTrait;
    use CommentTrait;
    use CountTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $comment = $this->checkComment($id);

        $validator = new UserLimitValidator();

        $validator->checkDailyCommentLimit($user);

        $parent = $comment;

        $validator = new CommentValidator();

        $data = $this->handlePostData($post);

        $data['parent_id'] = $parent->id;
        $data['item_id'] = $comment->item_id;
        $data['item_type'] = $comment->item_type;
        $data['owner_id'] = $user->id;
        $data['published'] = $this->getPublishStatus($user);

        $item = $validator->checkItem($comment->item_id, $comment->item_type);

        /**
         * 子评论中回复用户
         */
        if ($comment->parent_id > 0) {
            $parent = $validator->checkParent($comment->parent_id);
            $data['parent_id'] = $parent->id;
            $data['to_user_id'] = $comment->owner_id;
        }

        $reply = new CommentModel();

        $reply->create($data);

        $this->incrUserDailyCommentCount($user);

        if ($reply->published == CommentModel::PUBLISH_APPROVED) {
            $this->incrCommentReplyCount($parent);
            $this->incrItemCommentCount($item);
            $this->incrUserCommentCount($user);
            $this->handleCommentRepliedNotice($reply);
            $this->handleCommentPostPoint($reply);
        }

        $this->eventsManager->fire('Comment:afterReply', $this, $reply);

        return $reply;
    }

}
