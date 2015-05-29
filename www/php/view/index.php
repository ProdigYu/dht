<?php include 'header.php'; ?>
<body>
	<?php include 'menu.php'; ?>
	<?php include 'index_bg_mixin.php'; ?>
	<div id="main" class="container">
		<div id="index-search">
			<form action="/search" method="get">
				<input type="text" name="q" placeholder="搜索torrent" required="required">
				<input class="btn btn-primary" type="submit" value="搜索">
			</form>
		</div>
	</div>
</body>
<?php include 'footer.php'; ?>