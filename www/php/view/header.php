<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="/static/bower_components/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="/static/bower_components/bootstrap/dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="/static/bower_components/nprogress/nprogress.css">
		<link rel="stylesheet" href="/static/bower_components/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="/static/css/app.css">
		<title><?php echo !empty($title) ? $title : 'DHT search';?></title>
		<?php if (!empty($block_head)) echo $block_head; ?>
	</head>