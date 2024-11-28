<?php

namespace Wooping\ShopHealth\WordPress;

use Wooping\ShopHealth\Contracts\Interfaces\Hookable;
use Wooping\ShopHealth\Contracts\Router;

/**
 * Class HandleAdminRequest
 *
 * A class that handles admin requests by registering hooks and handling post or get requests.
 *
 * @extends Router
 * @implements Hookable
 */
class HandleAdminRequest extends Router implements Hookable {

	/**
	 * Register hooks
	 *
	 * @return void
	 */
	public function register_hooks(): void {
		// fetch all admin routes.
		$this->get_routes( 'admin' );

		// handle a post-request.
		if ( $this->is_valid_request( 'post' ) ) {
			\add_action( 'admin_init', [ $this, 'post' ] );

		}
		elseif ( $this->is_valid_request( 'get' ) ) {
			\add_action( 'admin_init', [ $this, 'get' ] );

		}
	}

	/**
	 * Handles any registered post requests
	 */
	public function post(): void {

		// Check if we're dealing with a post request and check nonce.
		// The nonce verification takes place in ValidNonce.
		$name  = \sanitize_text_field( $_GET['woop_request'] ); // phpcs:ignore
		$route = $this->get_route( $name, 'post' );

		// get the controller and method.
		$resolved_route = $this->get_trigger( $route );
		$controller     = $resolved_route[0];
		$method         = $resolved_route[1];

		// Run middleware with the method.
		if ( $controller->middleware( $method ) ) {

			// Call the middleware method.
			$controller->{$method}();

		}
	}

	/**
	 * Handles any registered get-requests
	 */
	public function get(): void {

		// Check if we're dealing with a post request and check nonce.
		// The nonce verification takes place in ValidNonce.
		$name  = \sanitize_text_field( $_GET['page'] ); // phpcs:ignore
		$route = $this->get_route( $name, 'get' );
		if ( \is_null( $route ) ) {
			$route = $this->get_route( $name, 'menu' );
		}

		// get the controller and method.
		$resolved_route = $this->get_trigger( $route );
		$controller     = $resolved_route[0];
		$method         = $resolved_route[1];

		// Try running middleware with the method and die if failed.
		if ( $controller->middleware( $method ) === false ) {

			\wp_die( 'Middleware went wrong.' );

		}
	}

	/**
	 * Return whether or not this route exists
	 *
	 * @param string $type The request type.
	 */
	protected function is_valid_request( $type ): bool {

		$type = \strtoupper( $type );

		// check request method.
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] !== $type ) {
			return false;
		}

		switch ( $type ) {

			case 'POST':
				// check if it's a valid shop-health route.
				// The nonce verification takes place in ValidNonce.
				$name = $_GET['woop_request'] ?? ''; // phpcs:ignore
				return ( ! \is_null( $this->get_route( $name, 'post' ) ) );

			case 'GET':
				// The nonce verification takes place in ValidNonce.
				$name = ( $_GET['page'] ?? '' ); // phpcs:ignore
				if ( \is_null( $name ) ) {
					return false;
				}

				if ( \is_null( $this->get_route( $name, 'get' ) )
					&& \is_null( $this->get_route( $name, 'menu' ) )
				) {
					return false;
				}

				return true;
		}

		return false;
	}
}
