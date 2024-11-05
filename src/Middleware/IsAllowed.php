<?php

namespace Wooping\ShopHealth\Middleware;

use Wooping\ShopHealth\Contracts\Interfaces\Middleware;

/**
 * Middleware class to check whether a user is logged in has manage_woocommerce capability
 * This is used to determine if a user can perform actions.
 */
class IsAllowed implements Middleware {

	/**
	 * Run the handle function
	 */
	public function handle(): bool {
		if ( ! \is_user_logged_in() ) {
			return false;
		}

		return \current_user_can( 'manage_woocommerce' );
	}
}
