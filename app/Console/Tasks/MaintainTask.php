<?php

namespace App\Console\Tasks;

use App\Library\Cache\Backend\Redis as RedisCache;
use Phalcon\Cli\Task;
use Phalcon\Config;

class MaintainTask extends Task
{

    public function mainAction()
    {
        $this->resetAnnotationAction();
        $this->resetMetadataAction();
        $this->resetVoltAction();
    }

    /**
     * 重置注解
     *
     * @command: php console.php maintain reset_annotation
     */
    public function resetAnnotationAction()
    {
        $config = $this->getConfig();
        $cache = $this->getCache();
        $redis = $cache->getRedis();

        $dbIndex = $config->path('annotation.db');
        $statsKey = $config->path('annotation.statsKey');

        $redis->select($dbIndex);

        $keys = $redis->sMembers($statsKey);

        echo "start reset annotation..." . PHP_EOL;

        if (count($keys) > 0) {

            $keys = $this->handleKeys($keys);

            $redis->del(...$keys);
            $redis->del($statsKey);
        }

        echo "end reset annotation..." . PHP_EOL;
    }

    /**
     * 重置元数据
     *
     * @command: php console.php maintain reset_metadata
     */
    public function resetMetadataAction()
    {
        $config = $this->getConfig();
        $cache = $this->getCache();
        $redis = $cache->getRedis();

        $dbIndex = $config->path('metadata.db');
        $statsKey = $config->path('metadata.statsKey');

        $redis->select($dbIndex);

        $keys = $redis->sMembers($statsKey);

        echo "start reset metadata..." . PHP_EOL;

        if (count($keys) > 0) {

            $keys = $this->handleKeys($keys);

            $redis->del(...$keys);
            $redis->del($statsKey);
        }

        echo "start reset metadata..." . PHP_EOL;
    }

    /**
     * 重置模板
     *
     * @command: php console.php maintain reset_volt
     */
    public function resetVoltAction()
    {
        echo "start reset volt..." . PHP_EOL;

        $dir = cache_path('volt');

        foreach (scandir($dir) as $file) {
            if (strpos($file, '.php')) {
                unlink($dir . '/' . $file);
            }
        }

        echo "end reset volt..." . PHP_EOL;
    }

    protected function getConfig()
    {
        /**
         * @var Config $config
         */
        $config = $this->getDI()->get('config');

        return $config;
    }

    protected function getCache()
    {
        /**
         * @var RedisCache $cache
         */
        $cache = $this->getDI()->get('cache');

        return $cache;
    }

    protected function handleKeys($keys)
    {
        return array_map(function ($key) {
            return "_PHCR{$key}";
        }, $keys);
    }

}
