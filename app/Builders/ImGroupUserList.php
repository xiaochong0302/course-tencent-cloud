<?php

namespace App\Builders;

use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\User as UserRepo;

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

        $columns = ['id', 'type', 'name', 'avatar', 'about', 'owner_id', 'user_count'];

        $groups = $groupRepo->findByIds($ids, $columns);

        $users = $this->getGroupOwners($groups->toArray());

        $baseUrl = kg_ci_base_url();

        $result = [];

        foreach ($groups->toArray() as $group) {
            $group['avatar'] = $baseUrl . $group['avatar'];
            $group['owner'] = $users[$group['owner_id']] ?? new \stdClass();
            unset($group['owner_id']);
            $result[$group['id']] = $group;
        }

        return $result;
    }

    protected function getGroupOwners(array $groups)
    {
        $ids = kg_array_column($groups, 'owner_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name']);

        $result = [];

        if ($users->count() > 0) {
            foreach ($users->toArray() as $user) {
                $result[$user['id']] = $user;
            }
        }

        return $result;
    }

}
