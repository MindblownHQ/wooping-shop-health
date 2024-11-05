<div class="progress-bar--panel panel <?php echo ( $scans_in_progress > 0 ? 'in-progress' : '' );?>" id="wooping-scan-progress">
	<h2 class="panel--header with-controls no-background">
		<?php echo __( 'Shop health scan in progress', 'wooping-shop-health' );?>
		<p class="message"></p>
	</h2>
	<div class="progress-bar">
		<div class="bar"></div>
		<span class="percentage"></span>
	</div>
</div>
