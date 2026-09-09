<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Console\Migrations;

use App\Console\Tasks\UpgradeTask;
use App\Library\Utils\Password as PasswordUtil;
use App\Models\Account as AccountModel;
use App\Models\Nav as NavModel;
use App\Models\Page as PageModel;
use App\Models\Role as RoleModel;
use App\Models\User as UserModel;
use App\Models\Vip as VipModel;
use App\Repos\Nav as NavRepo;
use App\Repos\Page as PageRepo;
use App\Repos\Role as RoleRepo;
use App\Repos\User as UserRepo;
use App\Repos\Vip as VipRepo;

class V20260905194518 extends Migration
{

    public function run(): void
    {
        /**
         * 后面的数据初始化依赖配置缓存（存储相关），需要先重置缓存
         */
        $this->resetSettingCache();

        $this->initUserData();
        $this->initRoleData();
        $this->initVipData();
        $this->initPageData();
        $this->initNavData();
    }

    protected function resetSettingCache(): void
    {
        $task = new UpgradeTask();

        $task->resetSettingAction();
    }

    protected function initUserData(): void
    {
        $id = 10000;
        $email = '10000@163.com';
        $salt = PasswordUtil::salt();
        $password = PasswordUtil::hash('123456', $salt);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($id);

        if ($user) return;

        $account = new AccountModel();

        $account->id = $id;
        $account->email = $email;
        $account->password = $password;
        $account->salt = $salt;

        $account->create();

        $userRepo = new UserRepo();

        $user = $userRepo->findById($id);

        $user->name = '酷瓜云课堂';
        $user->title = '官方人员';
        $user->about = '酷瓜云课堂，开源在线教育解决方案';
        $user->profile = '酷瓜云课堂，开源在线教育解决方案';
        $user->admin_role = RoleModel::ROLE_ROOT;
        $user->edu_role = UserModel::EDU_ROLE_TEACHER;

        $user->update();
    }

    protected function initRoleData(): void
    {
        $rows = [
            [
                'id' => RoleModel::ROLE_ROOT,
                'type' => RoleModel::TYPE_SYSTEM,
                'name' => '管理员',
                'summary' => '管理员',
                'user_count' => 1,
            ],
            [
                'id' => RoleModel::ROLE_OPERATOR,
                'type' => RoleModel::TYPE_SYSTEM,
                'name' => '运营',
                'summary' => '运营人员',
                'user_count' => 0,
            ],
            [
                'id' => RoleModel::ROLE_EDITOR,
                'type' => RoleModel::TYPE_SYSTEM,
                'name' => '编辑',
                'summary' => '编辑人员',
                'user_count' => 0,
            ],
            [
                'id' => RoleModel::ROLE_FINANCE,
                'type' => RoleModel::TYPE_SYSTEM,
                'name' => '财务',
                'summary' => '财务人员',
                'user_count' => 0,
            ],
        ];

        $roleRepo = new RoleRepo();

        foreach ($rows as $row) {
            $role = $roleRepo->findById($row['id']);
            if (!$role) {
                $role = new RoleModel();
                $role->assign($row);
                $role->create();
            }
        }
    }

    protected function initVipData(): void
    {
        $rows = [
            [
                'id' => 1,
                'title' => '1个月',
                'expiry' => 1,
                'price' => 60.00,
            ],
            [
                'id' => 2,
                'title' => '3个月',
                'expiry' => 3,
                'price' => 150.00,
            ],
            [
                'id' => 3,
                'title' => '6个月',
                'expiry' => 6,
                'price' => 240.00,
            ],
            [
                'id' => 4,
                'title' => '12个月',
                'expiry' => 12,
                'price' => 360.00,
            ],
        ];

        $vipRepo = new VipRepo();

        foreach ($rows as $row) {
            $vip = $vipRepo->findById($row['id']);
            if (!$vip) {
                $vip = new VipModel();
                $vip->assign($row);
                $vip->create();
            }
        }
    }

    protected function initPageData(): void
    {
        $rows = [
            [
                'id' => 1,
                'title' => '关于我们',
                'alias' => 'about',
                'published' => 1,
            ],
            [
                'id' => 2,
                'title' => '联系我们',
                'alias' => 'contact',
                'published' => 1,
            ],
            [
                'id' => 3,
                'title' => '用户协议',
                'alias' => 'terms',
                'published' => 1,
            ],
            [
                'id' => 4,
                'title' => '隐私政策',
                'alias' => 'privacy',
                'published' => 1,
            ],
        ];

        $pageRepo = new PageRepo();

        foreach ($rows as $row) {
            $page = $pageRepo->findById($row['id']);
            if (!$page) {
                $page = new PageModel();
                $page->assign($row);
                $page->create();
            }
        }
    }

    protected function initNavData(): void
    {
        $rows = [
            [
                'id' => 1,
                'parent_id' => 0,
                'level' => 1,
                'name' => '首页',
                'path' => ',1,',
                'target' => '_self',
                'url' => '/',
                'position' => 1,
                'priority' => 1,
                'published' => 1,
            ],
            [
                'id' => 2,
                'parent_id' => 0,
                'level' => 1,
                'name' => '课程',
                'path' => ',2,',
                'target' => '_self',
                'url' => '/course/list',
                'position' => 1,
                'priority' => 2,
                'published' => 1,
            ],
            [
                'id' => 3,
                'parent_id' => 0,
                'level' => 1,
                'name' => '师资',
                'path' => ',3,',
                'target' => '_self',
                'url' => '/teacher/list',
                'position' => 1,
                'priority' => 3,
                'published' => 1,
            ],
            [
                'id' => 4,
                'parent_id' => 0,
                'level' => 1,
                'name' => '关于我们',
                'path' => ',4,',
                'target' => '_blank',
                'url' => '/page/about',
                'position' => 2,
                'priority' => 1,
                'published' => 1,
            ],
            [
                'id' => 5,
                'parent_id' => 0,
                'level' => 1,
                'name' => '联系我们',
                'path' => ',5,',
                'target' => '_blank',
                'url' => '/page/contact',
                'position' => 2,
                'priority' => 2,
                'published' => 1,
            ],
            [
                'id' => 6,
                'parent_id' => 0,
                'level' => 1,
                'name' => '用户协议',
                'path' => ',6,',
                'target' => '_blank',
                'url' => '/page/terms',
                'position' => 2,
                'priority' => 3,
                'published' => 1,
            ],
            [
                'id' => 7,
                'parent_id' => 0,
                'level' => 1,
                'name' => '隐私政策',
                'path' => ',7,',
                'target' => '_blank',
                'url' => '/page/privacy',
                'position' => 2,
                'priority' => 4,
                'published' => 1,
            ],
        ];

        $navRepo = new NavRepo();

        foreach ($rows as $row) {
            $nav = $navRepo->findById($row['id']);
            if (!$nav) {
                $nav = new NavModel();
                $nav->assign($row);
                $nav->create();
            }
        }
    }

}
