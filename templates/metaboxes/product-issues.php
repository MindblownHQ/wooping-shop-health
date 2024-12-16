<?php
if ( $object->relevant_issues->isEmpty() ) {
	echo '<strong>' . esc_html__( 'No issues found.', 'wooping-shop-health' ) . '</strong>';
	echo '<p>' . esc_html__( 'Congratulations. No issues were found with your product, you are doing great!', 'wooping-shop-health' );
} else {
	?>
<div class="wsh-issues-table wsh-product-issues-table">
<div class="wsh-issues-table__issues-container wsh-product-issues-table">
	<header>
		<span class="">Description</span>
		<span class="">First seen</span>
		<span class="">Actions</span>
	</header>
	<div class="wsh-issues-table__issues">
		<?php foreach ( $object->relevant_issues as $issue ) { ?>
			<div class="wsh-issues-table__issue">
				<div class="wsh-issues-table__description">
					<span><?php echo esc_html( $issue->message ); ?></span>
					<a href="<?php echo esc_attr( $issue->docs_link ); ?>" target="_blank"
						class="<?php echo( ! is_null( $issue->docs_description ) ? 'docs_link has-tooltip' : 'docs_link' ); ?>">
						<span class="dashicons dashicons-welcome-learn-more"></span>
						<span><?php echo __( 'Learn more', 'wooping-shop-health' ); ?></span>
						<?php if ( ! is_null( $issue->docs_description ) ) : ?>
							<span class="tooltip">
															<?php echo $issue->docs_description; ?>
														</span>
						<?php endif; ?>
					</a>
				</div>
				<time datetime="<?php echo $issue->created_at->format( 'c' ); ?>" class="reported_on wsh-issues-table__cell"><?php echo $issue->created_at->format( 'd M Y' ); ?></time>
				<button class="button wsh-button wsh-button--ghost ignore-issue-btn"
						data-issue-id="<?php echo absint( $issue->id ); ?>">
					Ignore issue
				</button>
			</div>
		<?php } ?>
	</div>
</div>
</div>
<?php } ?>
