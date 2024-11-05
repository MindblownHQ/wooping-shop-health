<?php

namespace Wooping\ShopHealth\Validators\Products;

use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasGalleryImages
 *
 * This class extends the Validator class and represents a validator for checking if a product has gallery images.
 */
class HasGalleryImages extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 45;

	/**
	 * Does this product have gallery images?
	 *
	 * @return bool
	 */
	public function passes(): bool {
		return \count( $this->product->get_gallery_image_ids() ) > 0;
	}

	/**
	 * Actionable message / advice
	 *
	 * @return string
	 */
	public function message(): string {
		return \__( 'This product doesn\'t have any gallery images. Adding gallery images can enhance product presentation.', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Images serve two purposes, more product information and attraction.', 'wooping-shop-health' );
	}
}
