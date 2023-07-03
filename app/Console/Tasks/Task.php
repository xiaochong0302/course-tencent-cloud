<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Traits\Service as ServiceTrait;

class Task extends \Phalcon\Cli\Task
{

    use ServiceTrait;

    protected function successPrint($text)
    {
        echo "\033[32m {$text} \033[0m" . PHP_EOL;
    }

    protected function errorPrint($text)
    {
        echo "\033[31m {$text} \033[0m" . PHP_EOL;
    }

}
