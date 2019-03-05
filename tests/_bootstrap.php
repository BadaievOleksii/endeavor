<?php

namespace Endeavor;

$dir = __DIR__ . '/..';
require($dir . '/vendor/autoload.php');
define('RUNTIME_ROOT', $dir . '/runtime');
define('ROOT', $dir . '/');
define('APP_ROOT', $dir . '/app');
define('UNIT_TESTING', true);