<?php

namespace Wooping\ShopHealth\Validators;

/**
 * Class ProductContainer
 *
 * Holds all product validators.
 */
class ProductContainer {

	/**
	 * Return an array of all product validators
	 *
	 * @return array <string>
	 */
	public function validators(): array {

		$response  = [];
		$dir       = \SHOP_HEALTH_PATH . '/src/Validators/Products';
		$namespace = '\\Wooping\\ShopHealth\\Validators\\Products\\';

		$files       = \scandir( $dir );
		$not_allowed = [ '.', '..', '.DS_Store' ];

		foreach ( $files as $file ) {
			if ( ! \in_array( $file, $not_allowed, true ) ) {
				$class      = \str_replace( '.php', '', $file );
				$response[] = $namespace . $class;
			}
		}

		return $response;
	}
}
