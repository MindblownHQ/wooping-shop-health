<?php

namespace Wooping\ShopHealth\Traits;

use WC_Admin_Notices;

/**
 * Trait HasNotifications
 *
 * This trait provides functionality to add notifications to a class.
 *
 * Notifications can be added through the `add_notification` method
 * and will be handled by Woocommerce Admin Notices.
 */
trait HasNotifications {

	/**
	 * Adds a notification message to the notifications array.
	 *
	 * @param string $message The notification message to be added.
	 * @param string $name    The notification name.
	 *
	 * @return void
	 */
	public function add_notification( $message = '', $name = '' ): void {
		if ( empty( $name ) ) {
			$name = \wp_unique_id( 'woop_' );
		}
		if ( ! empty( $message ) ) {
			WC_Admin_Notices::add_custom_notice( $name, $message );
		}
	}
}
