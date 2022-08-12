<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User;

use App\Models\User as UserModel;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\UserTrait;

class UserInfo extends LogicService
{

    use UserTrait;

    public function handle($id)
    {
        $user = $this->checkUser($id);

        return $this->handleUser($user);
    }

    protected function handleUser(UserModel $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'title' => $user->title,
            'about' => $user->about,
            'area' => $user->area,
            'gender' => $user->gender,
            'vip' => $user->vip,
            'locked' => $user->locked,
            'deleted' => $user->deleted,
            'course_count' => $user->course_count,
            'article_count' => $user->article_count,
            'question_count' => $user->question_count,
            'answer_count' => $user->answer_count,
            'comment_count' => $user->comment_count,
            'vip_expiry_time' => $user->vip_expiry_time,
            'lock_expiry_time' => $user->lock_expiry_time,
            'active_time' => $user->active_time,
            'create_time' => $user->create_time,
            'update_time' => $user->update_time,
        ];
    }

}
