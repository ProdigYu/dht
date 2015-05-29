<?php

class BaseController{
	public function __construct(){
		$this->app = Container::get('app');
	}
}
