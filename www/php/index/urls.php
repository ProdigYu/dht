<?php

$app = Container::get('app');

$app->get('/', function(){
	$controller = new IndexController();
	return $controller->get();
});
