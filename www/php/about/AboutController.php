<?php

class AboutController extends BaseController{
	public function __construct(){
		parent::__construct();

		$this->bg_urls = [
			'Jack et la mécanique du coeur.png',
			'Jack et la mécanique du coeur-1.jpg',
			'Jack et la mécanique du coeur-2.png',
			'Jack et la mécanique du coeur-3.png',
			'Jack et la mécanique du coeur-4.jpg',
			'Jack et la mécanique du coeur-5.jpg',
			'Jack et la mécanique du coeur-6.jpg',
			'Jack et la mécanique du coeur-7.jpg',
			'Jack et la mécanique du coeur-8.jpg',
		];
	}

	public function get(){
		$bg_url = '/static/images/' . $this->bg_urls[mt_rand(0, count($this->bg_urls)-1)];
		$this->app->render('about.php', ['bg_url' => $bg_url]);
	}
}
