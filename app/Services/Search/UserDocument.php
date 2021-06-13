<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Search;

use App\Models\User as UserModel;
use Phalcon\Di\Injectable;

class UserDocument extends Injectable
{

    /**
     * 设置文档
     *
     * @param UserModel $user
     * @return \XSDocument
     */
    public function setDocument(UserModel $user)
    {
        $doc = new \XSDocument();

        $data = $this->formatDocument($user);

        $doc->setFields($data);

        return $doc;
    }

    /**
     * 格式化文档
     *
     * @param UserModel $user
     * @return array
     */
    public function formatDocument(UserModel $user)
    {
        $user->avatar = UserModel::getAvatarPath($user->avatar);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'title' => $user->title,
            'avatar' => $user->avatar,
            'about' => $user->about,
            'gender' => $user->gender,
            'area' => $user->area,
            'vip' => $user->vip,
        ];
    }

}
