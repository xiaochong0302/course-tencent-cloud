<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Comment;

use App\Services\Logic\CommentTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Comment as CommentValidator;

class CommentDelete extends LogicService
{

    use CommentTrait;
    use CountTrait;

    public function handle($id)
    {
        $comment = $this->checkComment($id);

        $user = $this->getLoginUser();

        $validator = new CommentValidator();

        $validator->checkOwner($user->id, $comment->owner_id);

        $comment->deleted = 1;

        $comment->update();

        if ($comment->parent_id > 0) {
            $parent = $validator->checkParent($comment->parent_id);
            $this->decrCommentReplyCount($parent);
        }

        $item = $validator->checkItem($comment->item_id, $comment->item_type);

        $this->decrItemCommentCount($item);
        $this->decrUserCommentCount($user);

        $this->eventsManager->fire('Comment:afterDelete', $this, $comment);
    }

}
