<?php
/**
 * Displays the product issie tab
 * @var ScannedObject $products ;
 * @var               $max_pages
 * @var               $current_page
 */

use Wooping\ShopHealth\Models\ScannedObject;

?>

<div class="wrap">
	<h1 class="screen-reader-text"><?php esc_html_e( 'Wooping Shop Health Dashboard', 'wooping-shop-health' ); ?></h1>
	<?php woop_template( 'components.scan-progress' ); ?>
	<section class="wsh-wrap">
		<?php woop_template( 'components.dashboard-tabs' ); ?>
		<section class="wsh-tabs-pane">
			<section class="wsh-tabs-pane__content">
				<?php 
				if( $products->pluck('relevant_issues')->isEmpty() ){
					woop_template( 'components.empty-state' );
				
				} else {?>
				<div class="wsh-issues-table wsh-product-issues-table">
					<header class="wsh-issues-table__header">
						<span
							class="wsh-issues-table__cell"><?php esc_html_e( 'Product', 'wooping-shop-health' ); ?></span>
						<span
							class="wsh-issues-table__cell"><?php esc_html_e( 'Health', 'wooping-shop-health' ); ?></span>
						<span
							class="wsh-issues-table__cell"><?php esc_html_e( 'Issues', 'wooping-shop-health' ); ?></span>
					</header>
					<?php
					foreach ( $products as $product ) { ?>
						<div class="wsh-issues-table__product">
							<figure class="wsh-issues-table__product-image">
								<?php
								$thumbnail = get_the_post_thumbnail( $product->object_id, 'thumbnail' );
								if ( empty( $thumbnail ) ) {
									$thumbnail = wc_placeholder_img( 'thumbnail' );
								}
								echo $thumbnail;
								?>
							</figure>
							<div class="wsh-issues-table__product-title">
								<?php echo sprintf( '<span>%s</span>', get_the_title( $product->object_id ) ); ?>
								<a href="<?php echo get_edit_post_link( $product->object_id ); ?>"><?php esc_html_e( 'Edit product', 'wooping-shop-health' ); ?></a>
							</div>
							<div class="wsh-issues-table__score wsh-issues-table__cell">
								<?php echo sprintf(
									'<span class="wsh-pill wsh-pill__score" style="--score: %1$d">%2$d</span>',
									esc_attr( $product->score ),
									esc_html( $product->score )
								); ?>
							</div>
							<span class="wsh-issues-table__cell" data-id="issue-count">
								<?php
								$issue_count = $product->relevant_issues->count();
								$issue_text  = _n( 'issue', 'issues', $issue_count, 'wooping-shop-health' );
								printf(
								/* translators: %1$d: number of issues, %2$s: "issue" or "issues" depending on count */
									'<span class="issue-number">%1$d</span> %2$s',
									$issue_count,
									$issue_text
								);
								?>
							</span>
							<div class="wsh-issues-table__issues-container">
								<header>
									<span class=""><?php esc_html_e( 'Description', 'wooping-shop-health' ); ?></span>
									<span class=""><?php esc_html_e( 'First seen', 'wooping-shop-health' ); ?></span>
									<span class=""><?php esc_html_e( 'Actions', 'wooping-shop-health' ); ?></span>
								</header>
								<div class="wsh-issues-table__issues">
									<?php foreach ( $product->relevant_issues as $issue ) { ?>
										<div class="wsh-issues-table__issue">
											<div class="wsh-issues-table__description">
												<span><?php echo esc_html( $issue->message ); ?></span>
												<?php if( ! is_null( $issue->docs_description ) ):?>
													<a href="<?php echo \esc_attr( $issue->docs_link ); ?>" target="_blank" class="docs_link has-tooltip">
														<span class="dashicons dashicons-welcome-learn-more"></span>
														<span><?php echo __( 'Learn more', 'wooping-shop-health' ); ?></span>
														<span class="tooltip">
															<?php echo esc_html( $issue->docs_description ); ?>
														</span>
													</a>
												<?php endif;?>
											</div>
											<time datetime="<?php echo $issue->created_at->format( 'c' ); ?>" class="reported_on wsh-issues-table__cell"><?php echo $issue->created_at->format( 'd M Y' ); ?></time>

											<button class="button wsh-button wsh-button--ghost ignore-issue-btn"
													data-issue-id="<?php echo \absint( $issue->id ); ?>">
												<?php esc_html_e( 'Ignore issue', 'wooping-shop-health' ); ?>
											</button>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
				<?php }?>
			</section>
			<!-- pagination: -->
			<?php if( !empty( $products ) && $max_pages > 1 ):?>
			<nav class="wsh-pagination">
				<?php
				$baseurl = \woop_get_route( 'product_issues' );
				for ( $i = 1; $i <= $max_pages; $i++ ) {

					if ( $i == $current_page ) {
						echo '<span class="wsh-pagination__item wsh-pagination--current">' . $i . '</span>';
					} else {
						$url = add_query_arg( 'current_page', $i, $baseurl );
						echo '<a href="' . $url . '" class="wsh-pagination__item">' . $i . '</a>';
					}
				}
				?>
			</nav>
			<?php endif;?>
		</section>
	</section>
</div>
