<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Repos\User as UserRepo;

class ReportList extends Builder
{

    public function handleUsers(array $reports)
    {
        $users = $this->getUsers($reports);

        foreach ($reports as $key => $report) {
            $reports[$key]['owner'] = $users[$report['owner_id']] ?? new \stdClass();
        }

        return $reports;
    }

    public function getUsers(array $reports)
    {
        $ids = kg_array_column($reports, 'owner_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
