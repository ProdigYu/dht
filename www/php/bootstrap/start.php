<?php

if (!defined('ROOT'))
	define('ROOT', __DIR__ . '/..');

date_default_timezone_set('Asia/Shanghai');

include ROOT . '/vendor/autoload.php';

include ROOT . '/lib/Autoloader.php';

include ROOT . '/util/pretty_print.php';
include ROOT . '/util/util.php';

Autoloader::registerAutoloader();
Autoloader::addDir([
	ROOT . '/lib',
	ROOT . '/util',
	ROOT . '/controller',
	ROOT . '/model',
	ROOT . '/index',
	ROOT . '/search',
	ROOT . '/detail',
	ROOT . '/about',
]);

// config
Environment::setEnvironmentOption(['file' => ROOT . '/config/app.php']);

Config::setConfigDir(ROOT . '/config');
Config::addConfig('app.php', 'app', Environment::getEnvironment());
// Config::addConfig('database.php', 'database', Environment::getEnvironment());
