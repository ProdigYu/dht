<?php

class Container{
	public static $data;

	public static function register($name, $class_or_closure, $params=null){
		if (is_string($class_or_closure)){
			if (is_null($params)){
				self::$data[$name] = new $class_or_closure();
			} else {

			}
		} else if (is_callable($class_or_closure)) {
			self::$data[$name] = call_user_func($class_or_closure);
		}
	}

	public static function get($name){
		if (!isset(self::$data[$name])){
			throw new Exception("there is no container for {$name}");
		}
		return self::$data[$name];
	}
}
