<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Services\Logic\Service as LogicService;
use App\Validators\Comment as CommentValidator;
use App\Validators\UserLimit as UserLimitValidator;

class CommentCreate extends LogicService
{

    use AfterCreateTrait;
    use CommentDataTrait;
    use CountTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyCommentLimit($user);

        $validator = new CommentValidator();

        $item = $validator->checkItem($post['item_id'], $post['item_type']);

        $comment = new CommentModel();

        $data = $this->handlePostData($post);

        $data['item_id'] = $post['item_id'];
        $data['item_type'] = $post['item_type'];
        $data['owner_id'] = $user->id;
        $data['published'] = $this->getPublishStatus($user);

        $comment->create($data);

        $this->incrUserDailyCommentCount($user);

        if ($comment->published == CommentModel::PUBLISH_APPROVED) {
            $this->incrItemCommentCount($item);
            $this->incrUserCommentCount($user);
            $this->handleItemCommentedNotice($item, $comment);
            $this->handleCommentPostPoint($comment);
        }

        $this->eventsManager->fire('Comment:afterCreate', $this, $comment);

        return $comment;
    }

}
