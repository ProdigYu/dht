<?php

function pretty_size($size){
	$pretty = '';
	if ($size < 1024*1024){
		$_size = $size/1024;
		$_size = number_format($_size, 2);
		$pretty =  $_size . 'KB';
	} else if ($size < 1024*1024*1024){
		$_size = $size/(1024*1024);
		$_size = number_format($_size, 2);
		$pretty = $_size . 'MB';
	} else {
		$_size = $size/(1024*1024*1024);
		$_size = number_format($_size, 2);
		$pretty = $_size . 'GB';
	}
	return $pretty;
}