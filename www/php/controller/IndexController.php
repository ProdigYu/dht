<?php

class IndexController extends BaseController{
	public function __construct(){
		parent::__construct();
	}

	public function get(){
		return $this->app->render('index.php');
	}
}
