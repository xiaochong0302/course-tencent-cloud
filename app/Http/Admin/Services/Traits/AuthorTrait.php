<?php
/**
 * @copyright Copyright (c) 2026 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services\Traits;

use App\Repos\User as UserRepo;

trait AuthorTrait
{

    protected function getAuthorOptions($includeTeachers = true)
    {
        $userRepo = new UserRepo();

        $adminRoleUsers = [];
        $teachRoleUsers = [];

        $admins = $userRepo->findAdminRoleUsers();

        if ($admins->count() > 0) $adminRoleUsers = $admins->toArray();

        $teachers = $userRepo->findTeachers();

        if ($teachers->count() > 0) $teachRoleUsers = $teachers->toArray();

        $allUsers = $adminRoleUsers;

        if ($includeTeachers) {
            $allUsers = array_merge($adminRoleUsers, $teachRoleUsers);
        }

        $options = [];
        $userIds = [];

        // 管理用户和教师可能重叠
        foreach ($allUsers as $user) {
            if (!in_array($user['id'], $userIds)) {
                $options[] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                ];
                $userIds[] = $user['id'];
            }
        }

        return $options;
    }

}
