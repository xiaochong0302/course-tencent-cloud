<?php
/**
 * @copyright Copyright (c) 2022 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Console\Migrations;

use App\Traits\Service as ServiceTrait;
use App\Traits\Setting as SettingTrait;
use Phalcon\Di\Injectable;

abstract class Migration extends Injectable
{

    use ServiceTrait;
    use SettingTrait;

    abstract public function run(): void;

}
