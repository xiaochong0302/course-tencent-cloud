<?php

namespace App\Caches;

use App\Models\ImChatGroup as ImChatGroupModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ImHotGroupList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'im_hot_group_list';
    }

    public function getContent($id = null)
    {
        $limit = 12;

        $groups = $this->findHotGroups($limit);

        if ($groups->count() == 0) {
            return [];
        }

        return $this->handleContent($groups);
    }

    /**
     * @param ImChatGroupModel[] $groups
     * @return array
     */
    protected function handleContent($groups)
    {
        $result = [];

        foreach ($groups as $group) {
            $result[] = [
                'id' => $group->id,
                'name' => $group->name,
                'avatar' => $group->avatar,
                'about' => $group->about,
                'user_count' => $group->user_count,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|ImChatGroupModel[]
     */
    public function findHotGroups($limit = 12)
    {
        return ImChatGroupModel::query()
            ->where('deleted = 0')
            ->orderBy('user_count DESC')
            ->limit($limit)
            ->execute();
    }

}
