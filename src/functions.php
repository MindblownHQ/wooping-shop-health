<?php
/**
 * Simple helper functions for shop health and other wooping plugins.
 */

use Wooping\ShopHealth\Views\View;

if ( ! function_exists( 'woop_get_route' ) ) {

	/**
	 * Get a route and construct a url from it.
	 */
	function woop_get_route( string $route_name ): string {

		// get all admin routes.
		$admin_routes = wooping_get_routes();
		$admin_routes = ( $admin_routes['admin'] ?? [] );

		// loop through them, they are arranged like: 'menu' => [ item, item ] or 'get' => [ item, item ].
		foreach ( $admin_routes as $category => $values ) {

			// loop through each of the routes, if their name matches with the route_name, we got a winner.
			foreach ( $values as $name => $route ) {
				if ( $name === $route_name ) {
					switch ( $category ) {
						case 'menu':
						case 'get':
							return admin_url( 'admin.php?page=woop_' . $name );
						case 'post':
							return admin_url( 'admin.php?woop_request=' . $name );
					}
				}
			}
		}

		return false;
	}
}


if ( ! function_exists( 'woop_nonce_field' ) ) {
	/**
	 * Echo a nonce field that abides by the router-conventions.
	 */
	function woop_nonce_field( string $route ): string {
		return wp_nonce_field( $route, 'wooping_nonce', false );
	}
}


if ( ! function_exists( 'woop_current_route' ) ) {
	/**
	 * Returns the current route, if available.
	 */
	function woop_current_route(): string {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			// Sniff ignore: we don't need to check for a nonce here.
			return ( wp_unslash( $_GET['woop_request'] ) ?? '' ); // phpcs:ignore 
		}
		else {
			// Sniff ignore: we don't need to check for a nonce here.
			return str_replace( 'woop_', '', ( wp_unslash( $_GET['page'] ) ?? '' ) ); // phpcs:ignore 
		}
	}
}


if ( ! function_exists( 'woop_is_route' ) ) {
	/**
	 * Check if the string provided matches the current route.
	 */
	function woop_is_route( string $route_name ): bool {
		return ( woop_current_route() === $route_name );
	}
}


if ( ! function_exists( 'woop_template' ) ) {

	/**
	 * Returns and includes template-file.
	 *
 	 * @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
	 */
	function woop_template( string $template_name, array $attributes = [], $base_dir = SHOP_HEALTH_PATH ): void {

		$template = str_replace( '.', '/', $template_name );
		$file     = $base_dir . '/templates/' . $template . '.php';
		if ( file_exists( $file ) ) {

			// Extract args if there are any, this isn't best practice but for now, we're ignoring that.
			if ( is_array( $attributes ) && count( $attributes ) > 0 ) {
				extract( $attributes );  // phpcs:ignore 
			}

			require_once $file;
		}
	}
}

if ( ! function_exists( 'woop_view' ) ) {
	/**
	 * Helper to return a view class.
	 *
 	 * @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
	 */
	function woop_view( string $template_name, array $attributes ): View {
		return ( new View() )->set( $template_name, $attributes );
	}
}

if ( ! function_exists( 'woop_get_link' ) ) {
	/**
	 * Add parameters to outgoing links
	 *
	 * @param string $url    The URL.
	 * @param string $plugin The plugin name.
	 *
	 * @return string The url with extra parameters.
	 */
	function woop_get_link( $url, $plugin = 'shop-health' ): string {

		$source = woop_current_route();

		if ( empty( $source ) ) {
			$screen = get_current_screen();
			$source = $screen && property_exists( $screen, 'base' ) ? $screen->base : 'unknown';

			if ( $screen && property_exists( $screen, 'post_type' ) ) {
				$source .= '-' . $screen->post_type;
			}
		}

		$query_args = [
			'utm_medium' => $plugin,
			'utm_source' => $source,
		];

		return add_query_arg( $query_args, trailingslashit( $url ) );
	}
}
