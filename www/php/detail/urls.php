<?php

$app = Container::get('app');

$app->get('/detail/:hash', function($hash){
	$controller = new DetailController();
	return $controller->get($hash);
});

$app->post('/detail', function(){
	$controller = new DetailController();
	return $controller->post();
});
