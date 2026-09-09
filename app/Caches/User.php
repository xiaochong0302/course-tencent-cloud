<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\User as UserModel;
use App\Repos\User as UserRepo;

class User extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 86400;

    public function getKey($id = null): string
    {
        return "user-{$id}";
    }

    public function getContent($id = null): ?UserModel
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($id);

        return $user ?: null;
    }

}
