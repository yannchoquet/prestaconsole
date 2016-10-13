<?php

$root = getcwd() . '/';
$autoloadFile = $root.'/config/config.inc.php';

if (file_exists($autoloadFile)) {
    $autoload = include_once $autoloadFile;
} else {
    echo PHP_EOL .'PrestaConsole must be executed within a PrestaShop Site.'.PHP_EOL;
    exit(1);
}

