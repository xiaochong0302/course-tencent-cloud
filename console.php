#!/usr/bin/env php

<?php

use Bootstrap\ConsoleKernel;

require __DIR__ . '/bootstrap/Kernel.php';
require __DIR__ . '/bootstrap/ConsoleKernel.php';

$kernel = new ConsoleKernel();

$kernel->handle();

