<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Caches\AppInfo as AppInfoCache;
use App\Caches\NavTreeList as NavTreeListCache;
use App\Caches\Setting as SettingCache;
use App\Models\MigrationTask as MigrationTaskModel;
use App\Models\Setting as SettingModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class UpgradeTask extends Task
{

    public function mainAction()
    {
        $this->migrateAction();
        $this->resetAppInfoAction();
        $this->resetSettingAction();
        $this->resetAnnotationAction();
        $this->resetMetadataAction();
        $this->resetVoltAction();
        $this->resetNavAction();
    }

    /**
     * 执行迁移
     *
     * @command: php console.php upgrade migrate
     */
    public function migrateAction()
    {
        $tasks = $this->findMigrationTasks();

        $versionList = [];

        if ($tasks->count() > 0) {
            $versionList = kg_array_column($tasks->toArray(), 'version');
        }

        $files = scandir(app_path('Console/Migrations'));

        foreach ($files as $file) {

            if (preg_match('/^V[0-9]+\.php$/', $file)) {

                $version = substr($file, 0, -4);

                if (!in_array($version, $versionList)) {

                    $startTime = time();

                    $className = "\App\Console\Migrations\\{$version}";
                    $obj = new $className();
                    $obj->run();

                    $endTime = time();

                    $task = new MigrationTaskModel();
                    $task->version = $version;
                    $task->start_time = $startTime;
                    $task->end_time = $endTime;
                    $task->create();

                    echo "------ console migration {$version} ok ------" . PHP_EOL;
                }
            }
        }
    }

    /**
     * 重置应用信息
     *
     * @command: php console.php upgrade reset_app_info
     */
    public function resetAppInfoAction()
    {
        echo '------ start reset app info ------' . PHP_EOL;

        $cache = new AppInfoCache();

        $cache->rebuild();

        echo '------ end reset app info ------' . PHP_EOL;
    }

    /**
     * 重置系统设置
     *
     * @command: php console.php upgrade reset_setting
     */
    public function resetSettingAction()
    {
        echo '------ start reset setting ------' . PHP_EOL;

        $rows = SettingModel::query()->columns('section')->distinct(true)->execute();

        foreach ($rows as $row) {
            $cache = new SettingCache();
            $cache->rebuild($row->section);
        }

        echo '------ end reset setting ------' . PHP_EOL;
    }

    /**
     * 重置注解
     *
     * @command: php console.php upgrade reset_annotation
     */
    public function resetAnnotationAction()
    {
        $redis = $this->getRedis();

        $statsKey = '_ANNOTATION_';

        $keys = $redis->sMembers($statsKey);

        echo '------ start reset annotation ------' . PHP_EOL;

        if (count($keys) > 0) {
            $keys = $this->handlePhKeys($keys);
            $redis->del(...$keys);
            $redis->del($statsKey);
        }

        echo '------ end reset annotation ------' . PHP_EOL;
    }

    /**
     * 重置元数据
     *
     * @command: php console.php upgrade reset_metadata
     */
    public function resetMetadataAction()
    {
        $redis = $this->getRedis();

        $statsKey = '_METADATA_';

        $keys = $redis->sMembers($statsKey);

        echo '------ start reset metadata ------' . PHP_EOL;

        if (count($keys) > 0) {
            $keys = $this->handlePhKeys($keys);
            $redis->del(...$keys);
            $redis->del($statsKey);
        }

        echo "------ end reset metadata ------" . PHP_EOL;
    }

    /**
     * 重置模板
     *
     * @command: php console.php upgrade reset_volt
     */
    public function resetVoltAction()
    {
        echo '------ start reset volt ------' . PHP_EOL;

        $dir = cache_path('volt');

        foreach (scandir($dir) as $file) {
            if (strpos($file, '.php')) {
                unlink($dir . '/' . $file);
            }
        }

        echo '------ end reset volt ------' . PHP_EOL;
    }

    /**
     * 重置导航
     *
     * @command: php console.php upgrade reset_nav
     */
    public function resetNavAction()
    {
        echo '------ start reset navigation ------' . PHP_EOL;

        $cache = new NavTreeListCache();

        $cache->delete();

        echo '------ end reset navigation ------' . PHP_EOL;
    }

    /**
     * @return ResultsetInterface|Resultset|MigrationTaskModel[]
     */
    protected function findMigrationTasks()
    {
        return MigrationTaskModel::query()->execute();
    }

    protected function handlePhKeys($keys)
    {
        return array_map(function ($key) {
            return "_PHCR{$key}";
        }, $keys);
    }

}
