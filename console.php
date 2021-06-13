#!/usr/bin/env php

<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Bootstrap\ConsoleKernel;

require __DIR__ . '/bootstrap/Kernel.php';
require __DIR__ . '/bootstrap/ConsoleKernel.php';

$kernel = new ConsoleKernel();

$kernel->handle();
