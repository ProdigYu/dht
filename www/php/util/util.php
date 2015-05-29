<?php

function getattr($arr, $key, $default=null){
	return !empty($arr[$key]) ? $arr[$key] : $default;
}
