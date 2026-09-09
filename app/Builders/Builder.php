<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Repos\Account as AccountRepo;
use App\Repos\User as UserRepo;
use Phalcon\Di\Injectable;

class Builder extends Injectable
{

    public function objects(array $items): array
    {
        return kg_objectify($items);
    }

    protected function getShallowUserByIds(array $ids): array
    {
        $userRepo = new UserRepo();

        $users = $userRepo->findShallowUserByIds($ids);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

    protected function getShallowAccountByIds(array $ids): array
    {
        $accountRepo = new AccountRepo();

        $accounts = $accountRepo->findShallowAccountByIds($ids);

        $result = [];

        foreach ($accounts->toArray() as $account) {
            $result[$account['id']] = $account;
        }

        return $result;
    }

}
