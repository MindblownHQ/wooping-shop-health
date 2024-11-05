<?php

namespace Wooping\ShopHealth\Middleware;

use Error;
use Wooping\ShopHealth\Contracts\Interfaces\Middleware;

/**
 * Check the validity of a nonce for every post action defined by routes in shop-health/src/routes.php
 */
class ValidNonce implements Middleware {

	/**
	 * Run the handle function.
	 *
	 * @throws Error If the nonce does not match the correct route.
	 */
	public function handle(): bool {
		// a non-post request always succeeds.
		if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
			return true;
		}

		// check if it's set.
		if ( ! isset( $_POST['wooping_nonce'] ) ) {
			return false;
		}

		// then verify.
		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$valid = \wp_verify_nonce( $_POST['wooping_nonce'], \woop_current_route() );
		if ( ! $valid ) {
			throw new Error( \esc_html__( 'Nonce doesn\'t match the current route', 'wooping-shop-health' ) );
		}

		return true;
	}
}
