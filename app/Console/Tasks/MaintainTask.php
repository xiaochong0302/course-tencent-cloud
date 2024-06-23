<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Http\Admin\Services\Setting as SettingService;
use App\Library\Utils\Password as PasswordUtil;
use App\Models\ChapterVod as ChapterVodModel;
use App\Services\Utils\IndexPageCache as IndexPageCacheUtil;
use App\Validators\Account as AccountValidator;

class MaintainTask extends Task
{

    /**
     * 重建首页课程缓存
     *
     * @param array $params
     * @command: php console.php maintain rebuild_index_course_cache
     */
    public function rebuildIndexCourseCacheAction($params)
    {
        $section = $params[0] ?? null;

        $util = new IndexPageCacheUtil();

        $util->rebuild($section);

        echo '------ rebuild index course cache success ------' . PHP_EOL;
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

        echo '------ reset password success ------' . PHP_EOL;
    }

    /**
     * 关闭站点
     *
     * @command: php console.php maintain disable_site
     */
    public function disableSiteAction()
    {
        $service = new SettingService();

        $service->updateSettings('site', ['status' => 'closed']);

        echo '------ disable site success ------' . PHP_EOL;
    }

    /**
     * 开启站点
     *
     * @command: php console.php maintain enable_site
     */
    public function enableSiteAction()
    {
        $service = new SettingService();

        $service->updateSettings('site', ['status' => 'normal']);

        echo '------ enable site success ------' . PHP_EOL;
    }

    /**
     * 清理点播转码缓存
     *
     * @command: php console.php maintain clear_file_transcode
     */
    public function clearFileTranscodeAction()
    {
        $chapterVodModel = new ChapterVodModel();

        $tableName = $chapterVodModel->getSource();

        $data = ['file_transcode' => '[]'];

        $fields = array_keys($data);

        $values = array_values($data);

        $where = ['conditions' => 'file_id > 0'];

        $this->db->update($tableName, $fields, $values, $where);

        echo '------ clear file transcode success ------' . PHP_EOL;
    }

}
