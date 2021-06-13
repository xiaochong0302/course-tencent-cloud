<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Models\User as UserModel;
use App\Traits\Client as ClientTrait;
use App\Validators\Comment as CommentValidator;

trait CommentDataTrait
{

    use ClientTrait;

    protected function handlePostData($post)
    {
        $data = [];

        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

        $validator = new CommentValidator();

        $data['content'] = $validator->checkContent($post['content']);

        return $data;
    }

    protected function getPublishStatus(UserModel $user)
    {
        $case1 = $user->article_count > 2;
        $case2 = $user->question_count > 2;
        $case3 = $user->answer_count > 2;
        $case4 = $user->comment_count > 2;

        $status = CommentModel::PUBLISH_PENDING;

        if ($case1 || $case2 || $case3 || $case4) {
            $status = CommentModel::PUBLISH_APPROVED;
        }

        return $status;
    }

}
