<?php
/**
 * Displays the general issues page.
 *
 * @var Collection    $objects
 * @var ScannedObject $object
 * @var Issue         $issue
 */

use Illuminate\Database\Eloquent\Collection;
use Wooping\ShopHealth\Models\Issue;
use Wooping\ShopHealth\Models\ScannedObject;

?>
<div class="wrap">
	<h1 class="screen-reader-text"><?php esc_html_e( 'Wooping Shop Health Dashboard', 'wooping-shop-health' ); ?></h1>
	<?php woop_template( 'components.header' ); ?>
	<div class="wrap wsh-dashboard__main">
		<?php woop_template( 'components.scan-progress' ); ?>
		<section class="wsh-wrap">
			<?php woop_template( 'components.dashboard-tabs' ); ?>
			<section class="wsh-tabs-pane">
				<header class="wsh-tabs-pane__header">
					<h1><?php esc_html_e( 'General issues', 'wooping-shop-health' ); ?></h1>
					<p><?php esc_html_e( 'This is an overview of all the issues we found in your WooCommerce settings.', 'wooping-shop-health' ); ?></p>
				</header>
				<section class="wsh-tabs-pane__content">
					<?php
					if ( $objects->isEmpty() ) {
						woop_template( 'components/empty-state' );
					} else {
						?>
						<div class="wsh-issues-table wsh-general-issues-table">
							<header class="wsh-issues-table__header">
								<span class="wsh-issues-table__cell"><?php esc_html_e( 'Issue', 'wooping-shop-health' ); ?></span>
								<span class="wsh-issues-table__cell"><?php esc_html_e( 'First seen', 'wooping-shop-health' ); ?></span>
							</header>
							<div class="wsh-issues-table__issues-container">
								<?php
								foreach ( $objects as $object ) {
									foreach ( $object->issues as $issue ) {
										?>
										<div class="wsh-issues-table__description wsh-issues-table__cell">
											<span><?php echo esc_html( $issue->message ); ?></span>
											<?php if ( ! is_null( $issue->docs_description ) ) : ?>
												<a href="<?php echo esc_attr( $issue->docs_link ); ?>" target="_blank" class="docs_link has-tooltip">
													<span class="dashicons dashicons-welcome-learn-more"></span>
													<span><?php echo __( 'Learn more', 'wooping-shop-health' ); ?></span>
													<span class="tooltip"><?php echo esc_html( $issue->docs_description ); ?></span>
												</a>
											<?php endif; ?>
										</div>
										<time datetime="<?php echo $issue->created_at->format( 'c' ); ?>" class="reported_on wsh-issues-table__cell"><?php echo $issue->created_at->format( 'd M Y' ); ?></time>
										<?php
									}
								}
								?>
							</div>
						</div>
						<?php
					}
					?>
				</section>
			</section>
		</section>
	</div>
</div>
