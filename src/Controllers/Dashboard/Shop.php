<?php

namespace Wooping\ShopHealth\Controllers\Dashboard;

use Wooping\ShopHealth\Contracts\Controller;
use Wooping\ShopHealth\Models\ScannedObject;

/**
 * Class ShopController
 *
 *  Controller for triggering shop scans and scheduling validation jobs.
 */
class Shop extends Controller {

	/**
	 * Display the shop issues.
	 *
	 * @return void
	 */
	public function display(): void {

		$objects = ScannedObject::where( 'object_type', '!=', 'product' )
				->whereHas( 'relevant_issues' )
				->get();

		\woop_view( 'general-issues', \compact( 'objects' ) )->render();
	}
}
