<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Http\Admin\Services\Setting as SettingService;
use App\Library\Utils\Password as PasswordUtil;
use App\Repos\Chapter as ChapterRepo;
use App\Services\ChapterVod as ChapterVodService;
use App\Services\Utils\IndexPageCache as IndexPageCacheUtil;
use App\Validators\Account as AccountValidator;

class MaintainTask extends Task
{

    /**
     * 重建首页课程缓存
     *
     * @command: php console.php maintain rebuild_index_page_cache {?section}
     */
    public function rebuildIndexPageCacheAction(?string $section = null): void
    {
        $util = new IndexPageCacheUtil();

        $util->rebuild($section);

        echo '------ rebuild index page cache success ------' . PHP_EOL;
    }

    /**
     * 修改密码
     *
     * @command: php console.php maintain reset_password {account} {password}
     */
    public function resetPasswordAction(string $account, string $password): void
    {
        if (strlen($account) == 0) {
            exit('account is required' . PHP_EOL);
        }

        if (strlen($password) == 0) {
            exit('password is required' . PHP_EOL);
        }

        $validator = new AccountValidator();

        $account = $validator->checkAccount($account);

        $salt = PasswordUtil::salt();
        $hash = PasswordUtil::hash($password, $salt);

        $account->salt = $salt;
        $account->password = $hash;

        $account->update();

        echo '------ reset password success ------' . PHP_EOL;
    }

    /**
     * 关闭站点
     *
     * @command: php console.php maintain disable_site
     */
    public function disableSiteAction(): void
    {
        $service = new SettingService();

        $service->updateMySettings('site', ['status' => 'closed']);

        echo '------ disable site success ------' . PHP_EOL;
    }

    /**
     * 开启站点
     *
     * @command: php console.php maintain enable_site
     */
    public function enableSiteAction(): void
    {
        $service = new SettingService();

        $service->updateMySettings('site', ['status' => 'normal']);

        echo '------ enable site success ------' . PHP_EOL;
    }

    /**
     * 重置点播信息缓存
     *
     * @command: php console.php maintain reset_vod_file_cache {chapterId}
     */
    public function resetVodFileCacheAction(int $chapterId)
    {
        if (strlen($chapterId) == 0) {
            exit('chapter is required' . PHP_EOL);
        }

        $chapterRepo = new ChapterRepo();

        $chapterVod = $chapterRepo->findChapterVod($chapterId);

        if (!$chapterVod) {
            exit('chapter not found' . PHP_EOL);
        }

        $service = new ChapterVodService();

        $service->pullMediaInfo($chapterVod);

        echo '------ reset vod file cache success ------' . PHP_EOL;
    }

}
