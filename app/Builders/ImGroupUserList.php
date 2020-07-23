<?php

namespace App\Builders;

use App\Repos\ImGroup as ImGroupRepo;

class ImGroupUserList extends Builder
{

    public function handleGroups(array $relations)
    {
        $groups = $this->getGroups($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['group'] = $groups[$value['group_id']] ?? new \stdClass();
        }

        return $relations;
    }

    public function getGroups(array $relations)
    {
        $ids = kg_array_column($relations, 'group_id');

        $groupRepo = new ImGroupRepo();

        $columns = ['id', 'type', 'name', 'avatar', 'about', 'user_count'];

        $groups = $groupRepo->findByIds($ids, $columns);

        $baseUrl = kg_ci_base_url();

        $result = [];

        foreach ($groups->toArray() as $group) {
            $group['group'] = $baseUrl . $group['avatar'];
            $result[$group['id']] = $group;
        }

        return $result;
    }

}
