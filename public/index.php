<?php

use Bootstrap\HttpKernel;

require '../bootstrap/Kernel.php';
require '../bootstrap/HttpKernel.php';

$kernel = new HttpKernel();

$kernel->handle();