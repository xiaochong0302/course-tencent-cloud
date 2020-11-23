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

    public function handleUsers(array $relations)
    {
        $users = $this->getUsers($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['user'] = $users[$value['user_id']] ?? new \stdClass();
        }

        return $relations;
    }

    public function getUsers(array $relations)
    {
        $ids = kg_array_column($relations, 'user_id');

        $userRepo = new UserRepo();

        $columns = ['id', 'name', 'avatar', 'title', 'about', 'vip', 'gender', 'area'];

        $users = $userRepo->findByIds($ids, $columns);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

    public function getGroups(array $relations)
    {
        $ids = kg_array_column($relations, 'group_id');

        $groupRepo = new ImGroupRepo();

        $columns = ['id', 'type', 'name', 'avatar', 'about', 'owner_id', 'user_count', 'msg_count'];

        $groups = $groupRepo->findByIds($ids, $columns);

        $users = $this->getGroupOwners($groups->toArray());

        $baseUrl = kg_cos_url();

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
