<?php include 'header.php'; ?>
<body ng-app="ngApp">
	<?php include 'menu.php'; ?>
	<div id="search-body" ng-controller='searchController'>
		<div id="main" class="container search-main">
			<div id="search-result">
				<div style="height:30px;"></div>

				<div class="search-form">
					<form action="/search" method="get">
						<input type="text" name="q" required="required" value="<?php echo $q;?>">
						<input class="btn btn-primary" type="submit" value="搜索">
					</form>
				</div>

				<div class="search-count">
					<?php if ($total > 0){ ?>
					<span>找到 <?php echo $total;?> 条结果</span>
					<?php } else { ?>
					<span>未找到相关结果</span>
					<?php } ?>
				</div>

				<div class="search-result">
					<?php if (!empty($result)) { ?>
					<?php foreach ($result as $k => $v) { ?>
						<div class="search-row" hash="<?php echo getattr($v, 'hash', '');?>">
							<div class="name-line">
								<a href="/detail/<?php echo getattr($v, 'hash', '#');?>">
									<?php echo $v['name']; ?>
								</a>
							</div>
							<div class="intro">
								<span>
									<a href="<?php echo getattr($v, 'magnet', '');?>">
										磁力链接
									</a>
								</span>
								<span>
									大小: <?php echo $v['pretty_size']; ?>
								</span>
								<span>
									创建日期: <?php echo getattr($v, 'creation_date', '0000-00-00 00:00:00'); ?>
								</span>
							</div>
						</div>
					<?php } ?>
					<?php } ?>
				</div>

			</div><!-- end id=search-result -->
		</div><!-- end id=main class=search-main -->
		<input type="hidden" name="q" id="input-q" value="<?php echo $q;?>">
		<input type="hidden" name="p" id="input-p" value="<?php echo $p;?>">
		<input type="hidden" name="has_more" id="input-has-more" value="<?php echo $has_more;?>">
		<script id="search-row-handlebars" type="text/x-handlebars-template">
			{{#result}}
				<div class="search-row" hash="{{hash}}">
					<div class="name-line">
						<a href="/detail/{{hash}}">
							{{{name}}}
						</a>
					</div>
					<div class="intro">
						<span>
							<a href="{{magnet}}">
								磁力链接
							</a>
						</span>
						<span>
							大小: {{pretty_size}}
						</span>
						<span>
							创建日期: {{creation_date}}
						</span>
					</div>
				</div>
			{{/result}}
		</script>
		<script id="search-detail-handlebars" type="text/x-handlebars-template">
			<div class="name">{{name}}</div>
			<div class="filelist">
				<p>文件列表:</p>
				{{#filelist}}
					<div class="filelist-row">
						<div class="filename">{{filepath}}</div>
						<div class="size">{{pretty_size}}</div>
						<div class="clear"></div>
					</div>
				{{/filelist}}
			</div>
		</script>
	</div>
</body>
<?php include 'footer.php'; ?>