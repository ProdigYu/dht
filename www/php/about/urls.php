<?php

$app = Container::get('app');

$app->get('/about/?', function(){
	$controller = new AboutController();
	return $controller->get();
});
