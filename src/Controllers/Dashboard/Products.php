<?php

namespace Wooping\ShopHealth\Controllers\Dashboard;

use Wooping\ShopHealth\Contracts\Controller;
use Wooping\ShopHealth\Models\ScannedObject;

/**
 * Controller for the products overview dashboard page.
 */
class Products extends Controller {

	/**
	 * Display the product issues view
	 */
	public function display(): void {

		// set per page and current page variables.
		$per_page     = 40;
		$current_page = 1;
		// phpcs:disable
		if ( isset( $_GET['current_page'] ) && \is_numeric( $_GET['current_page'] ) ) {
			$current_page = \absint( $_GET['current_page'] );
		}
		// phpcs:enable

		// calculate pagination offset.
		$offset    = ( ( $current_page - 1 ) * $per_page );
		$total     = ScannedObject::where( 'object_type', 'product' )->where( 'score', '<', 100 )->count();
		$max_pages = \ceil( $total / $per_page );

		$products = ScannedObject::where( 'object_type', 'product' )
				->whereHas( 'relevant_issues' )
				->with( [ 'relevant_issues' ] )
				->offset( $offset )
				->limit( $per_page )
				->get();

		\woop_view( 'product-issues', \compact( 'products', 'max_pages', 'current_page' ) )->render();
	}
}
