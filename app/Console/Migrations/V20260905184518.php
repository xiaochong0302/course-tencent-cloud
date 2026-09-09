<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Console\Migrations;

use App\Library\Utils\Password as PasswordUtil;
use App\Models\Account as AccountModel;
use App\Models\Nav as NavModel;
use App\Models\Page as PageModel;
use App\Models\Role as RoleModel;
use App\Models\User as UserModel;
use App\Models\Vip as VipModel;
use App\Repos\User as UserRepo;

class V20260905184518 extends Migration
{

    public function run(): void
    {
        $this->initSettings();
        $this->initUserData();
        $this->initRoleData();
        $this->initVipData();
        $this->initPageData();
        $this->initNavData();
    }

    protected function initSettings(): void
    {
        $this->initSecretSettings();
        $this->initSiteSettings();
        $this->initMailSettings();
        $this->initSmsSettings();
        $this->initCosSettings();
        $this->initVodSettings();
        $this->initPaySettings();
        $this->initSecuritySettings();
        $this->initContactSettings();
    }

    protected function initUserData(): void
    {
        $salt = PasswordUtil::salt();
        $password = PasswordUtil::hash('123456', $salt);

        $account = new AccountModel();

        $account->id = 10000;
        $account->email = '10000@163.com';
        $account->password = $password;
        $account->salt = $salt;

        $account->save();

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

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

        foreach ($rows as $row) {
            $role = new RoleModel();
            $role->assign($row);
            $role->save();
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

        foreach ($rows as $row) {
            $vip = new VipModel();
            $vip->assign($row);
            $vip->save();
        }
    }

    protected function initPageData(): void
    {
        $rows = [
            [
                'id' => 1,
                'title' => '关于我们',
                'alias' => 'about',
                'content' => '',
                'published' => 1,
            ],
            [
                'id' => 2,
                'title' => '联系我们',
                'alias' => 'contact',
                'content' => '',
                'published' => 1,
            ],
            [
                'id' => 3,
                'title' => '用户协议',
                'alias' => 'terms',
                'content' => '',
                'published' => 1,
            ],
            [
                'id' => 4,
                'title' => '隐私政策',
                'alias' => 'privacy',
                'content' => '',
                'published' => 1,
            ],
        ];

        foreach ($rows as $row) {
            $page = new PageModel();
            $page->assign($row);
            $page->save();
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

        foreach ($rows as $row) {
            $nav = new NavModel();
            $nav->assign($row);
            $nav->save();
        }
    }

    protected function initSecretSettings(): void
    {
        $settings = [
            'app_id' => 'xxx',
            'secret_id' => 'xxx',
            'secret_key' => 'xxx',
        ];

        $this->saveSettings('secret', $settings);
    }

    protected function initSiteSettings(): void
    {
        $settings = [
            'analytics_enabled' => '0',
            'analytics_script' => '',
            'closed_tips' => '站点维护中，请稍后再访问！',
            'company_sn' => '',
            'company_sn_link' => '',
            'copyright' => 'xxx有限公司',
            'description' => '',
            'favicon' => '',
            'icp_link' => 'https://beian.miit.gov.cn',
            'icp_sn' => '',
            'icp_op_link' => 'https://beian.miit.gov.cn',
            'icp_op_sn' => '',
            'index_tpl_type' => 'simple',
            'keywords' => '',
            'license' => '',
            'logo' => '',
            'police_link' => '',
            'police_sn' => '',
            'status' => 'normal',
            'title' => '酷瓜云课堂',
            'url' => '',
        ];

        $this->saveSettings('site', $settings);
    }

    protected function initMailSettings(): void
    {
        $settings = [
            'smtp_host' => '',
            'smtp_port' => '465',
            'smtp_username' => '',
            'smtp_password' => '',
            'smtp_from_email' => '',
            'smtp_from_name' => '',
            'smtp_auth_enabled' => '1',
            'smtp_encryption' => 'ssl',
        ];

        $this->saveSettings('mail', $settings);
    }

    protected function initSmsSettings(): void
    {
        $settings = [
            'app_id' => '',
            'app_key' => '',
            'region' => 'ap-guangzhou',
            'signature' => '',
            'template' => [
                'verify' => [
                    'enabled' => '1',
                    'id' => '0',
                ],
            ],
        ];

        $this->saveSettings('sms', $settings);
    }

    protected function initCosSettings(): void
    {
        $settings = [
            'protocol' => 'https',
            'domain' => '',
            'region' => '',
            'bucket' => '',
        ];

        $this->saveSettings('cos', $settings);
    }

    protected function initVodSettings(): void
    {
        $settings = [
            'audio_format' => 'mp3',
            'audio_quality' => [
                'sd',
            ],
            'domain' => '',
            'keep_origin_media' => '0',
            'key_anti_enabled' => '1',
            'key_anti_expiry' => '10800',
            'key_anti_ip_limit' => '3',
            'key_anti_key' => '',
            'protocol' => 'https',
            'std_trans_enabled' => '1',
            'storage_region' => '',
            'storage_type' => 'nearby',
            'sub_app_id' => '',
            'transcode_type' => 'normal',
            'video_format' => 'hls',
            'video_quality' => [
                'sd',
            ],
            'wmk_enabled' => '1',
            'wmk_tpl_id' => '843994',
        ];

        $this->saveSettings('vod', $settings);
    }

    protected function initPaySettings(): void
    {
        $settings = [
            'enabled' => '1',
            'app_id' => '',
            'app_secret_cert' => '',
            'notify_url' => '',
            'return_url' => '',
            'service_rate' => '10',
        ];

        $this->saveSettings('pay.alipay', $settings);

        $settings = [
            'enabled' => '1',
            'app_id' => '',
            'mch_id' => '',
            'mch_secret_key' => '',
            'mp_app_id' => '',
            'mini_app_id' => '',
            'wechat_public_key_id' => '',
            'notify_url' => '',
            'return_url' => '',
            'service_rate' => '10',
        ];

        $this->saveSettings('pay.wxpay', $settings);
    }

    protected function initSecuritySettings(): void
    {
        $settings = [
            'private' => '0',
            'failed_login_limit' => '5',
            'failed_login_lock' => '600',
            'mutex_client_limit' => '1',
            'mutex_login' => '1',
        ];

        $this->saveSettings('security.access', $settings);

        $settings = [
            'register_with_phone' => '1',
            'register_with_email' => '1',
        ];

        $this->saveSettings('security.register', $settings);

        $settings = [
            'enabled' => '1',
            'interval' => '900',
            'rate_limit' => '900',
        ];

        $this->saveSettings('security.throttle', $settings);

        $settings = [
            'enabled' => '0',
            'content' => '',
        ];

        $this->saveSettings('security.blacklist', $settings);
    }

    protected function initContactSettings(): void
    {
        $settings = [
            'enabled' => '1',
            'phone' => '',
            'email' => '',
            'address' => '',
            'weibo' => '',
            'zhihu' => '',
            'douyin' => '',
            'toutiao' => '',
            'wechat' => '',
            'qq' => '',
        ];

        $this->saveSettings('contact', $settings);
    }

}
