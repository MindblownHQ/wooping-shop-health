<?php

namespace Wooping\ShopHealth\Contracts;

use Wooping\ShopHealth\Models\ScannedObject;

/**
 * Blueprint for a page validator.
 * Can be used to validate pages such as WooCommerce cart or checkout page or other pages such as product overview, categories or tags.
 */
abstract class PageValidator extends Validator {

	/**
	 * Defined the page that is being validated. Can be a post id or archive page.
	 *
	 * @var int|null $object_id
	 */
	protected $object_id;

	/**
	 * Constructor
	 *
	 * @var string|int $object_id
	 */
	public function __construct( ScannedObject $scanned_object ) {
		$this->object = $scanned_object;
	}
}
