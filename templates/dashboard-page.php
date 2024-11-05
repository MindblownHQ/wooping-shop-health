<?php
/**
 * Displays the main dashboard page.
 *
 * @var int   $shop_score
 * @var Issue $shop_issues
 * @var Issue $pressing_product_issues
 * @var int   $product_score
 * @var Issue $product_issues
 * @var array $stats
 * @var bool  $has_hpos
 */

use Wooping\ShopHealth\Models\Issue;

$current_user = wp_get_current_user();
?>
<div class="wrap">
	<h1 class="screen-reader-text"><?php esc_html_e( 'Wooping Shop Health Dashboard', 'wooping-shop-health' ); ?></h1>
	<?php woop_template( 'components.header' ); ?>
	<div class="wrap wsh-dashboard__main">
		<?php woop_template( 'components.scan-progress', compact( 'scans_in_progress' ) ); ?>
		<section class="wsh-wrap">
			<?php woop_template( 'components.dashboard-tabs' ); ?>
			<section class="wsh-tabs-pane">
				<header class="wsh-tabs-pane__header">
					<div class="wsh-tabs-pane__header--with-controls">
						<div class="wsh-tab-pane__intro">
							<h1><?php esc_html_e( 'Welcome', 'wooping-shop-health' ); ?></h1>
							<p><?php esc_html_e( 'Welcome to the Wooping Shop Health dashboard. On this page you\'ll find an overview of the current status of your WooCommerce shop.', 'wooping-shop-health' ); ?></p>
						</div>
						<div class="wsh-scan-control">
							<button class="button button-primary scan-all"><?php esc_html_e( 'Scan all', 'wooping-shop-health' ); ?></button>
							<?php if( !is_null( $last_scan ) ):?>
								<p class="wsh-scan-control__last-scan"><?php echo esc_html( $last_scan );?></p>
							<?php endif;?>
						</div>
					</div>
				</header>
				<section class="wsh-tabs-pane__content">
					<section class="wsh-statistics">
						<header class="wsh-statistics__header">
							<div class="wsh-statistics__pillars">
								<h2><?php esc_html__( 'Pillars of growth' ,'wooping-shop-health' ); ?></h2>
								<?php
								echo '<p>';
								/* translators: %1$s / %2$s expand to a link tag that leads to the Shop Health documentation pages */
								$message = esc_html__( 'We measure the success of your shop by three pillars. More information on these pillars can be found on %1$sour website%2$s', 'wooping-shop-health' );
								echo sprintf( $message, '<a href="' . woop_get_link(\trailingslashit( SHOP_HEALTH_DOCUMENTATION_URL ) . 'pillars-of-growth' ) . '" target="_blank">', '</a>' );
								echo '</p>';
								?>
							</div>
							<?php printf(
							/* translators: %s is the URL for refreshing the statistics */
								'<button class="wsh-pill wsh-button wsh-button--ghost wooping-refresh-statistics">%1$s</button>',
								__( 'Refresh', 'wooping-shop-health' ),
							); ?>
						</header>
						<div class="wsh-widgets__container <?php echo ( ! $has_hpos ? 'hpos-disabled' : '' ); ?>">
							<?php if ( ! $has_hpos ) { ?>
								<div class="wsh-widgets__container__overlay">
									<div class="hpos-notification">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
										<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
										</svg>

										<h2>
											<?php esc_html_e( 'Please enable high performance order storage', 'wooping-shop-health' ); ?>
										</h2>
										<p>
											<?php esc_html_e( 'Sadly, we can\'t show you more information about your site because you are still working with a legacy version of WooCommerce order storage.', 'wooping-shop-health' ); ?>
										</p>
										<p>
											<a href="<?php echo SHOP_HEALTH_DOCUMENTATION_URL; ?>/hpos">
												<?php esc_html_e( 'Learn more about why this is important', 'wooping-shop-health' ); ?>
											</a>
										</p>
									</div>
								</div>
							<?php } ?>
							<?php
							if (is_array($stats)) {
								\woop_view(
									'components/statistics', [
										'data' => $stats['returning'],
										'type' => 'number',
										'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-6" viewBox="0 0 24 24"><path d="M14.6 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm1.4 7.7c.552 0 1.01-.452.9-.994a5.002 5.002 0 0 0-9.802 0c-.109.542.35.994.902.994h8ZM4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z"/></svg>',
									]
								)->render();
								\woop_view(
									'components/statistics', [
										'data' => $stats['customers'],
										'type' => 'number',
										'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-6" viewBox="0 0 24 24"><path d="M5.25 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0Zm-3 12.75a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM18.75 7.5a.75.75 0 0 0-1.5 0v2.25H15a.75.75 0 0 0 0 1.5h2.25v2.25a.75.75 0 0 0 1.5 0v-2.25H21a.75.75 0 0 0 0-1.5h-2.25V7.5Z"/></svg>',
									]
								)->render();
								\woop_view(
									'components/statistics', [
										'data' => $stats['revenue'],
										'type' => 'currency',
										'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-6" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M2.25 2.25a.75.75 0 0 0 0 1.5H3v10.5a3 3 0 0 0 3 3h1.21l-1.172 3.513a.75.75 0 0 0 1.424.474l.329-.987h8.418l.33.987a.75.75 0 0 0 1.422-.474l-1.17-3.513H18a3 3 0 0 0 3-3V3.75h.75a.75.75 0 0 0 0-1.5H2.25Zm6.54 15h6.42l.5 1.5H8.29l.5-1.5Zm8.085-8.995a.75.75 0 1 0-.75-1.299 12.81 12.81 0 0 0-3.558 3.05L11.03 8.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l2.47-2.47 1.617 1.618a.75.75 0 0 0 1.146-.102 11.312 11.312 0 0 1 3.612-3.321Z" clip-rule="evenodd"/></svg>',
									]
								)->render();
									
							}
							?>
						</div>
					</section>
					<section class="wsh-analytics wsh-widgets__container">
						<section class="wsh-box wsh-analytics__chart" data-location="1">
							<header class="wsh-box__header">
								<?php
								echo sprintf( '<h2 class="wsh-box__title">%s</h2>', esc_html__( 'Shop Health', 'wooping-shop-health' ) );
								?>
							</header>
							<?php
							woop_view(
								'components/donut-chart', [
									'success'      => $shop_score,
									'warning'      => 100 - $shop_score,
									'issues_count' => $shop_issues->count()
								]
							)->render();
							?>
							<a class="wsh-analytics__link has-icon"
							   href="<?php echo woop_get_route( 'shop_issues' ); ?>">
								<span><?php esc_html_e( 'See all shop issues', 'wooping-shop-health' ); ?></span>
								<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="none"
									 viewBox="0 0 24 24"
									 stroke-width="1.5" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
								</svg>
							</a>
						</section>
						<section class="wsh-box wsh-analytics__chart" data-location="2">
							<header class="wsh-box__header">
								<?php
								echo sprintf( '<h2 class="wsh-box__title">%s</h2>', esc_html__( 'Product Health', 'wooping-shop-health' ) );
								?>
							</header>
							<?php
							woop_view(
								'components/donut-chart', [
									'success'      => $product_score,
									'warning'      => 100 - $product_score,
									'issues_count' => $product_issues->count()
								]
							)->render();
							?>
							<a class="wsh-analytics__link has-icon"
							   href="<?php echo woop_get_route( 'product_issues' ); ?>">
								<span><?php esc_html_e( 'See all product issues', 'wooping-shop-health' ); ?></span>
								<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="none"
									 viewBox="0 0 24 24"
									 stroke-width="1.5" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
								</svg>
							</a>
						</section>
						<section class="wsh-box wsh-pressing-issues" data-location="pressing">
							<header class="wsh-box__header">
								<h2 class="wsh-box__title"><?php \esc_html_e( 'Pressing Issues', 'wooping-shop-health' ); ?></h2>
								<p><?php esc_html_e('These are the most pressing issues that require attention.', 'wooping-shop-health');  ?></p>
							</header>

							<h3><?php \esc_html_e( 'Products', 'wooping-shop-health' ); ?></h3>
							<ul class="issues--list">
								<?php if ( ! $pressing_product_issues->isEmpty() ) { ?>
									<?php foreach ( $pressing_product_issues as $issue ) { ?>
										<li>
											<a href="<?php echo get_edit_post_link( $issue->scanned_object->object_id ); ?>"><?php echo get_the_title( $issue->scanned_object->object_id ); ?></a>
											<br/><?php echo $issue->message; ?>
										</li>
									<?php }
								} ?>
							</ul>
							<a class="link has-icon"
							   href="<?php echo \woop_get_route( 'product_issues' ); ?>">
								<span><?php \esc_html_e( 'See all product issues', 'wooping-shop-health' ); ?></span>
								<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="none"
									 viewBox="0 0 24 24"
									 stroke-width="1.5" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round"
										  d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
								</svg>
							</a>
							<h3><?php \esc_html_e( 'Shop', 'wooping-shop-health' ); ?></h3>
							<?php if ( ! $shop_issues->isEmpty() ) { ?>
								<?php $max = $shop_issues->count() < 5 ? $shop_issues->count() : 5; ?>
								<ul class="issues--list">
									<?php foreach ( $shop_issues->random( $max ) as $issue ) { ?>
										<li>
											<?php echo $issue->message; ?>
										</li>
									<?php } ?>
								</ul>
								<a class="link has-icon" href="<?php echo \woop_get_route( 'shop_issues' ); ?>">
									<span><?php \esc_html_e( 'See all shop issues', 'wooping-shop-health' ); ?></span>
									<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="none"
										 viewBox="0 0 24 24"
										 stroke-width="1.5" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round"
											  d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
									</svg>
								</a>
							<?php } else { ?>
								<?php _e( 'No issues found!', 'wooping-shop-health' ); ?>
							<?php } ?>
							</ul>
						</section>
						<section class="wsh-box wsh-subscribe" data-location="subscribe">
							<header class="wsh-box__header">
								<?php echo sprintf( '<h2 class="wsh-box__title">%s</h2>', esc_html__( 'Subscribe', 'wooping-shop-health' ) ); ?>
								<?php echo sprintf( '<p>%s</p>', esc_html__( 'Want to receive periodical tips on how to improve your WooCommerce webshop and stay updated on Wooping.io plugins? Please enter your email and name below and submit. Let\'s stay in touch!', 'wooping-shop-health' ) ); ?>
							</header>
							<form class="wsh-form wsh-newsletter-subscribe">
								<div class="wsh-form__field-group">
									<label for="name"><?php esc_html_e('First name', 'wooping-shop-health'); ?></label>
									<input id="name" type="text" name="name" required
										   value="<?php echo esc_attr( $current_user->first_name ); ?>"/>
								</div>
								<div class="wsh-form__field-group">
									<label for="email"><?php esc_html_e('Email address', 'wooping-shop-health'); ?></label>
									<input id="email" type="email" name="email" required
										   value="<?php echo esc_attr( $current_user->user_email ); ?>" required/>
								</div>
								<button type="submit" class="button button-primary"><?php esc_html_e('Subscribe', 'wooping-shop-health'); ?></button>
							</form>
						</section>
					</section>
				</section>
			</section>
		</section>
	</div>
