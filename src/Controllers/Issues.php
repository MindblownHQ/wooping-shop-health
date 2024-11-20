<?php

namespace Wooping\ShopHealth\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Wooping\ShopHealth\Contracts\Controller;
use Wooping\ShopHealth\Models\Issue;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Class IssuesController
 *
 * This class is responsible for handling issue changes.
 */
class Issues extends Controller {

	/**
	 * Set the status of an issue
	 *
	 * @return bool True if the status is successfully updates, false if not.
	 */
	public static function set_status( WP_REST_Request $request ): WP_REST_Response {

		$issue_id = $request->get_param( 'id' );
		$status   = $request->get_param( 'status' );

		// we also need a status.
		$status = \sanitize_text_field( $status );
		if ( ! \in_array( $status, Issue::STATUSES, true ) ) {
			return false;
		}

		try {
			// save the issue with the new status.
			$issue         = Issue::findOrFail(  $issue_id ); // phpcs:ignore
			$issue->status = $status;
			$issue->save();

			return new WP_REST_Response(
				[
					'status'        => 'success',
					'message'       => \__( 'The issue status has been updated.', 'wooping-shop-health' ),
					'score'         => $scanned_object->score,
					'issue_count'   => $scanned_object->relevant_issues()->count(),
				],
				200
			);

		} catch ( ModelNotFoundException $e ) {

			// @todo: do error handling
			return new WP_REST_Response(
				[
					'status'  => 'error',
					'message' => \__( 'Something went wrong while updating the issue status.', 'wooping-shop-health' ),
				],
				500
			);
		}
	}
}
