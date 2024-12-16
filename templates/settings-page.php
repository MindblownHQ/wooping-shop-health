<div class="wrap">
	<h1 class="screen-reader-text"><?php esc_html_e( 'Wooping Shop Health Dashboard', 'wooping-shop-health' ); ?></h1>
	<?php woop_template( 'components.header' ); ?>
	<div class="wrap wsh-dashboard__main wsh-settings">
		<section class="wsh-wrap">
			<?php woop_template( 'components.setting-tabs' ); ?>
			<section class="wsh-tabs-pane">
				<section class="wsh-tabs-pane__content">
					<form class="panel" action="<?php echo woop_get_route( 'save_settings' ); ?>" method="POST">
						<?php echo woop_nonce_field( 'save_settings' ); ?>
						<section class="panel--header with-controls">
							<h2><?php esc_html_e( 'Ignored issues', 'wooping-shop-health' ); ?></h2>
							<p><?php esc_html_e( 'Which of these issues would you like to ignore throughout the site?', 'wooping-shop-health' ); ?></p>
						</section>
						<?php $ignored = $settings['ignored_validators']; ?>
						<ul class="mass-ignore-issues">
							<li class="row">
								<label class="issue-description" for="ignoreHasDimensions"
										title="<?php echo esc_attr( esc_html__( 'Disable product dimension checks', 'wooping-shop-health' ) ); ?>'">
									<strong><?php esc_html_e( 'Product Dimensions', 'wooping-shop-health' ); ?></strong><br>
									<?php esc_html_e( 'Checks whether or not your products have a height, width and weight defined.', 'wooping-shop-health' ); ?>
								</label>
								<input type="checkbox" <?php echo checked( in_array( 'HasDimensions', $ignored ) ); ?>
										id="ignoreHasDimensions" name="ignored_validators[]" value="HasDimensions">
							</li>
							<li class="row">
								<label class="issue-description" for="ignoreHasEan"
										title="<?php echo esc_attr( esc_html__( 'Disable product EAN checks', 'wooping-shop-health' ) ); ?>'">
									<strong><?php esc_html_e( 'EAN', 'wooping-shop-health' ); ?></strong><br>
									<?php esc_html_e( 'Checks if the product has an EAN defined. Legally required if you\'re selling in the European Union.', 'wooping-shop-health' ); ?>
								</label>
								<input type="checkbox" <?php echo checked( in_array( 'HasEan', $ignored ) ); ?>
										id="ignoreHasEan" name="ignored_validators[]" value="HasEan">
							</li>
							<li class="row">
								<label class="issue-description" for="ignoreHasGalleryImages"
										title="<?php echo esc_attr( esc_html__( 'Disable product gallery checks', 'wooping-shop-health' ) ); ?>'">
									<strong><?php esc_html_e( 'Gallery Images', 'wooping-shop-health' ); ?></strong><br>
									<?php esc_html_e( 'This validator checks if you\'ve uploaded images for a products\' gallery.', 'wooping-shop-health' ); ?>
								</label>
								<input type="checkbox" <?php echo checked( in_array( 'HasGalleryImages', $ignored ) ); ?>
										id="ignoreHasGalleryImages" name="ignored_validators[]" value="HasGalleryImages">
							</li>
							<li class="row">
								<label class="issue-description" for="ignoreHasTags"
										title="<?php echo esc_attr( esc_html__( 'Disable product tags checks', 'wooping-shop-health' ) ); ?>'">
									<strong><?php esc_html_e( 'Tags', 'wooping-shop-health' ); ?></strong><br>
									<?php esc_html_e( 'Checks whether or not your products have tags associated with them', 'wooping-shop-health' ); ?>
								</label>
								<input type="checkbox" <?php echo checked( in_array( 'HasTags', $ignored ) ); ?>
										id="ignoreHasTags" name="ignored_validators[]" value="HasTags">
							</li>
							<li class="row">
								<label class="issue-description" for="ignoreHasOpenGraph"
										title="<?php echo esc_attr( esc_html__( 'Disable product open-graph checks', 'wooping-shop-health' ) ); ?>'">
									<strong><?php esc_html_e( 'Open Graph', 'wooping-shop-health' ); ?></strong><br>
									<?php esc_html_e( 'This validator checks for open-graph information on the product page.', 'wooping-shop-health' ); ?>
								</label>
								<input type="checkbox" <?php echo checked( in_array( 'HasOpenGraph', $ignored ) ); ?>
										id="ignoreHasOpenGraph" name="ignored_validators[]" value="HasOpenGraph">
							</li>
							<li class="row">
								<label class="issue-description" for="ignoreHasStock"
										title="<?php echo esc_attr( esc_html__( 'Disable product stock checks', 'wooping-shop-health' ) ); ?>'">
									<strong><?php esc_html_e( 'Product Stock', 'wooping-shop-health' ); ?></strong><br>
									<?php esc_html_e( 'Checks whether or not your products has enough stock if stock keeping is enabled.', 'wooping-shop-health' ); ?>
								</label>
								<input type="checkbox" <?php echo checked( in_array( 'HasStock', $ignored ) ); ?>
										id="ignoreHasStock" name="ignored_validators[]" value="HasStock">
							</li>
						</ul>
						<button class="button button-primary"><?php esc_html_e( 'Save settings', 'wooping-shop-health' ); ?></button>
					</form>
				</section>
			</section>
		</section>
	</div>
</div>
