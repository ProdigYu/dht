<?php

class IndexController extends BaseController{
	public function __construct(){
		parent::__construct();
		$this->bg_urls = [
			'Jack et la mécanique du coeur.png',
		];
	}

	public function get(){
		$bg_url = '';
		return $this->app->render('index.php');
	}
}
