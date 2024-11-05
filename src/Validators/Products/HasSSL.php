<?php

namespace Wooping\ShopHealth\Validators\Products;

use Throwable;
use Wooping\ShopHealth\Contracts\ProductValidator;

/**
 * Class HasSSL
 *
 * This class extends the Validator class and represents a validator for checking if a product has a valid SSL certificate.
 */
class HasSSL extends ProductValidator {

	/**
	 * Define the importance of this validator.
	 */
	public const SEVERITY = 100;

	/**
	 * Does this product have a valid SSL certificate?
	 */
	public function passes(): bool {
		try{
			return $this->object->html()->get_status_code() !== 495;
		}catch( Throwable $error ){
			return false;
		}
	}

	/**
	 * Actionable message / advice
	 */
	public function message(): string {
		return \__( 'This product or page has an invalid SSL certificate', 'wooping-shop-health' );
	}

	/**
	 * Returns a single line of documentation for this validator.
	 */
	public static function documentation(): string {
		return \__( 'Security is key, at a minimum your shop should implement an SSL certificate.', 'wooping-shop-health' );
	}
}
