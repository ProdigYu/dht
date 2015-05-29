<?php

include dirname(__DIR__) . '/bootstrap/start.php';

$app = new \Slim\Slim([
	'debut' => true,
	'templates.path' => ROOT . '/view',
]);

Container::register('app', function() use($app){
	return $app;
});

// require routes
include ROOT . '/index/urls.php';
include ROOT . '/search/urls.php';
include ROOT . '/detail/urls.php';
include ROOT . '/about/urls.php';

$app->run();
