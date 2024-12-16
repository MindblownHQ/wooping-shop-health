<?php
$tabs = apply_filters(
	'wooping_settings_tabs',
	[
		'settings' => esc_html__( 'Ignored issues', 'wooping-shop-health' ),
	]
);
?>

<section class="wsh-tabs__wrapper">
	<nav class="wsh-tabs__tabs" role="tablist">
		<?php
		foreach ( $tabs as $id => $title ) {
			printf(
				'<a class="wsh-tabs__tab" href="%1$s" aria-selected="%2$s">%3$s</a>',
				esc_url( woop_get_route( $id ) ),
				esc_attr( woop_is_route( $id ) ? 'true' : 'false' ),
				esc_html( $title ),
			);
		}
		?>
	</nav>
</section>
