<?php

namespace Wooping\ShopHealth\Queue;

use Wooping\ShopHealth\Contracts\Queueable;
use Wooping\ShopHealth\Models\Issue;

/**
 * Class CheckBulkIgnoredValidators
 *
 * This class is responsible for bulk-ignoring or un-ignoring validators that have been removed from the list.
 */
class CheckBulkIgnoredValidators extends Queueable {

	/**
	 * Hook on which this job runs
	 */
	protected string $hook = 'wooping/shop-health/check_bulk_ignored_validators';

	/**
	 * This job updates issues and makes them more important as they grow older
	 *
	 * @param array<string> $old_array An array of previous ignored validators.
	 * @param array<string> $new_array An fresh array of ignored validators.
	 *
	 * @return void
	 */
	public function run( array $old_array, array $new_array ): void {

		// get the validators to un-ignore and to (re)-ignore.
		$unignore = \collect( $old_array )->diff( $new_array );
		$ignore   = \collect( $new_array );

		// unignored validators we need to update to resolved, so they'll automatically
		// re-appear after saving a product.
		if ( ! $unignore->isEmpty() ) {
			foreach ( $unignore->all() as $validator ) {
				Issue::where( 'validator', $validator )->update( [ 'status' => 'resolved' ] );
			}
		}

		// ignored issues, we can just update to 'ignore'.
		if ( ! $ignore->isEmpty() ) {
			foreach ( $ignore->all() as $validator ) {
				Issue::where( 'validator', $validator )->update( [ 'status' => 'ignored' ] );
			}
		}
	}
}
