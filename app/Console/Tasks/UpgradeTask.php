<?php

namespace App\Console\Tasks;

use App\Caches\Setting as SettingCache;
use App\Models\Setting as SettingModel;

class UpgradeTask extends Task
{

    public function mainAction()
    {
        $this->resetSettingAction();
        $this->resetAnnotationAction();
        $this->resetMetadataAction();
        $this->resetVoltAction();
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

        echo "end reset metadata..." . PHP_EOL;
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

    protected function handlePhKeys($keys)
    {
        return array_map(function ($key) {
            return "_PHCR{$key}";
        }, $keys);
    }

}
