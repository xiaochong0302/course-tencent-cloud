<?php

namespace App\Caches;

use App\Models\User as UserModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ImHotUserList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'im_hot_user_list';
    }

    public function getContent($id = null)
    {
        $limit = 12;

        $users = $this->findHotUsers($limit);

        if ($users->count() == 0) {
            return [];
        }

        return $this->handleContent($users);
    }

    /**
     * @param UserModel[] $users
     * @return array
     */
    protected function handleContent($users)
    {
        $result = [];

        foreach ($users as $user) {
            $result[] = [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'about' => $user->about,
                'location' => $user->location,
                'gender' => $user->gender,
                'vip' => $user->vip,
                'follower_count' => $user->follower_count,
                'following_count' => $user->following_count,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    public function findHotUsers($limit = 12)
    {
        return UserModel::query()
            ->where('deleted = 0')
            ->orderBy('follower_count DESC')
            ->limit($limit)
            ->execute();
    }

}
