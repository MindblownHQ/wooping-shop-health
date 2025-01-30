<?php
$tabs = apply_filters( 'wooping/settings/tabs', [
	'shop_health_settings' => esc_html__( 'Ignored issues', 'wooping-shop-health' ),
]);
?>

<section class="wsh-tabs__wrapper">
	<nav class="wsh-tabs__tabs" role="tablist">
		<?php
		foreach ( $tabs as $id => $title ) {
			echo sprintf( '<a class="wsh-tabs__tab" href="%1$s" aria-selected="%2$s">%3$s</a>',
				esc_url( conductor_get_route_url( $id ) ),
				esc_attr( woop_is_route( $id ) ? 'true' : 'false' ),
				esc_html( $title ),
			);
		}
		?>
	</nav>
</section>
