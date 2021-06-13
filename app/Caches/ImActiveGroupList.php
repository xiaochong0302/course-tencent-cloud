<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\ImMessage;
use App\Models\ImMessage as ImMessageModel;
use App\Models\User as UserModel;
use App\Repos\ImGroup as ImGroupRepo;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class ImActiveGroupList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'im_active_group_list';
    }

    public function getContent($id = null)
    {
        $groups = $this->findGroups();

        if (empty($groups)) {
            return [];
        }

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
     * @param int $days
     * @param int $limit
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    protected function findGroups($days = 7, $limit = 12)
    {
        $result = [];

        $startTime = strtotime("-{$days} days");
        $endTime = time();

        $rows = ImMessageModel::query()
            ->columns(['receiver_id', 'total_count' => 'count(receiver_id)'])
            ->groupBy('receiver_id')
            ->orderBy('total_count DESC')
            ->where('receiver_type = :type:', ['type' => ImMessageModel::TYPE_GROUP])
            ->betweenWhere('create_time', $startTime, $endTime)
            ->limit($limit)
            ->execute();

        if ($rows->count() > 0) {

            $ids = kg_array_column($rows->toArray(), 'receiver_id');

            $groupRepo = new ImGroupRepo();

            $result = $groupRepo->findByIds($ids);
        }

        return $result;
    }

}
