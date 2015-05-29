<div class="pjax-search-detail">
	<div class="name">
		<?php echo $name;?>
	</div>
	<div class="intro">
		<span>
			<a href="<?php echo $magnet;?>">
				磁力链接
			</a>
		</span>
		<span>
			大小: <?php echo $total_size;?>
		</span>
		<span>
			创建日期: <?php echo $creation_date;?>
		</span>
	</div>
	<div class="filelist">
		<p>文件列表:</p>
		<?php foreach ($filelist as $k => $file) { ?>
			<div class="filelist-row">
				<div class="filename">
					<?php echo $file['filepath'];?>
				</div>
				<div class="size">
					<?php echo $file['pretty_size']; ?>
				</div>
				<div class="clear"></div>
			</div>
		<?php } ?>
	</div>
</div>