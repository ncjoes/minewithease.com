<?php
declare(strict_types=1);

use Illuminate\Support\Str;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'constants.php';

//Load helper files
$files = scandir(__DIR__ . DS . 'helpers');
foreach ($files as $file) {
    if ($file === '.' || $file === '..' || !Str::endsWith($file, '.php')) {
        continue;
    }
    require_once __DIR__ . DS . 'helpers' . DS . $file;
}

/**
 * Don't declare functions here, keep it clean, every helper goes into Helpers
 * Don't know where to classify it? place it in Helpers/sundry.php
 *
 */
