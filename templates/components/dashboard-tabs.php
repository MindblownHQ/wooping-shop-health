<?php
$tabs = [
	'dashboard'      => esc_html__( 'Dashboard', 'wooping-shop-health' ),
	'shop_issues'    => esc_html__( 'Shop Issues', 'wooping-shop-health' ),
	'product_issues' => esc_html__( 'Product Issues', 'wooping-shop-health' ),
];
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
