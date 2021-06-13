<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Bootstrap\HttpKernel;

require '../bootstrap/Kernel.php';
require '../bootstrap/HttpKernel.php';

$kernel = new HttpKernel();

$kernel->handle();