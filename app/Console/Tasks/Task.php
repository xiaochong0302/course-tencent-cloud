<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Traits\Service as ServiceTrait;
use App\Traits\Setting as SettingTrait;

class Task extends \Phalcon\Cli\Task
{

    use ServiceTrait;
    use SettingTrait;
    protected function successPrint(string $text): void
    {
        echo "\033[32m {$text} \033[0m" . PHP_EOL;
    }

    protected function errorPrint(string $text): void
    {
        echo "\033[31m {$text} \033[0m" . PHP_EOL;
    }

    protected function infoPrint(string $text): void
    {
        echo "\033[36m {$text} \033[0m" . PHP_EOL;
    }

    protected function getTaskLockKey(?string $key = null): string
    {
        $key = $key ? sprintf('cli:%s', $key) : get_called_class();

        return md5($key);
    }

}
