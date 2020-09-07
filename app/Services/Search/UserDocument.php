<?php

namespace App\Services\Search;

use App\Models\User as UserModel;
use Phalcon\Mvc\User\Component;

class UserDocument extends Component
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
