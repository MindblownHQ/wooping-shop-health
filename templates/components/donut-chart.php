<?php
/**
 * @var int $success
 * @var int $warning
 * @var int $issues_count
 */
?>


<div class="wsh-donut-chart" style="--success-percentage:<?php echo $success; ?>; --warning-percentage:<?php echo $warning; ?>">
	<div class="wsh-donut-chart__center">
		<strong class="wsh-donut-chart__count"><?php echo $success; ?>%</strong>
		<?php if ( $issues_count > 0 ) { ?>
			<span class="wsh-donut-chart__issues">
			<?php
			printf(
				/* translators: %d: number of open issues */
				_n(
					'%d open issue',
					'%d open issues',
					$issues_count,
					'wooping-shop-health'
				),
				$issues_count
			);
			?>
		</span>
		<?php } ?>
	</div>
</div>

