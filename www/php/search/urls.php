<?php

$app = Container::get('app');

$app->get('/search/?', function(){
	$controller = new SearchController();
	return $controller->get();
});

$app->post('/search/?', function(){
	$controller = new SearchController();
	return $controller->post();
});
