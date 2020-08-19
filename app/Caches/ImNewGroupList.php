<?php

namespace App\Caches;

use App\Models\ImGroup as ImGroupModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ImNewGroupList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'im_new_group_list';
    }

    public function getContent($id = null)
    {
        $limit = 12;

        $groups = $this->findGroups($limit);

        if ($groups->count() == 0) {
            return [];
        }

        return $this->handleContent($groups);
    }

    /**
     * @param ImGroupModel[] $groups
     * @return array
     */
    protected function handleContent($groups)
    {
        $result = [];

        foreach ($groups as $group) {
            $result[] = [
                'id' => $group->id,
                'type' => $group->type,
                'name' => $group->name,
                'avatar' => $group->avatar,
                'about' => $group->about,
                'user_count' => $group->user_count,
                'msg_count' => $group->msg_count,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|ImGroupModel[]
     */
    public function findGroups($limit = 12)
    {
        return ImGroupModel::query()
            ->where('published = 1')
            ->orderBy('id DESC')
            ->limit($limit)
            ->execute();
    }

}
