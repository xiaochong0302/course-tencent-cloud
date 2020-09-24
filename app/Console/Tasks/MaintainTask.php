<?php

namespace App\Console\Tasks;

use App\Caches\IndexFreeCourseList as IndexFreeCourseListCache;
use App\Caches\IndexNewCourseList as IndexNewCourseListCache;
use App\Caches\IndexVipCourseList as IndexVipCourseListCache;
use App\Http\Admin\Services\Setting as SettingService;
use App\Library\Utils\Password as PasswordUtil;
use App\Validators\Account as AccountValidator;

class MaintainTask extends Task
{

    /**
     * 重建首页课程缓存
     *
     * @param array $params
     * @command: php console.php maintain reset_index_course_cache
     */
    public function rebuildIndexCourseCacheAction($params)
    {
        $section = $params[0] ?? null;

        if (!$section || $section == 'new_course') {
            $cache = new IndexNewCourseListCache();
            $cache->rebuild();
        }

        if (!$section || $section == 'free_course') {
            $cache = new IndexFreeCourseListCache();
            $cache->rebuild();
        }

        if (!$section || $section == 'vip_course') {
            $cache = new IndexVipCourseListCache();
            $cache->rebuild();
        }
    }

    /**
     * 修改密码
     *
     * @param array $params
     * @command: php console.php maintain reset_password 13507083515 123456
     */
    public function resetPasswordAction($params)
    {
        if (empty($params[0])) {
            echo 'account is required' . PHP_EOL;
        }

        if (empty($params[1])) {
            echo 'password is required' . PHP_EOL;
        }

        $validator = new AccountValidator();

        $account = $validator->checkAccount($params[0]);

        $salt = PasswordUtil::salt();
        $hash = PasswordUtil::hash($params[1], $salt);

        $account->salt = $salt;
        $account->password = $hash;

        $account->update();

        echo 'reset password success' . PHP_EOL;
    }

    /**
     * 关闭验证码
     *
     * @command: php console.php maintain disable_captcha
     */
    public function disableCaptchaAction()
    {
        $service = new SettingService();

        $service->updateSettings('captcha', ['enabled' => 0]);

        echo 'disable captcha success' . PHP_EOL;
    }

    /**
     * 启用验证码
     *
     * @command: php console.php maintain enable_captcha
     */
    public function enableCaptchaAction()
    {
        $service = new SettingService();

        $service->updateSettings('captcha', ['enabled' => 1]);

        echo 'enable captcha success' . PHP_EOL;
    }

}
