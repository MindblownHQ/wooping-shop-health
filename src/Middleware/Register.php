<?php

namespace Wooping\ShopHealth\Middleware;

use Wooping\ShopHealth\Contracts\Interfaces\Middleware;

/**
 * Registers all Middleware and
 * provides an interface to get middleware instances back from slugs
 */
class Register {

	/**
	 * Returns the correct instance of a piece of middleware
	 */
	public function get_instance( string $slug ): ?Middleware {
		$middleware = $this->available_middleware();
		if ( isset( $middleware[ $slug ] ) ) {
			return $middleware[ $slug ];
		}

		return null;
	}

	/**
	 * Registry of all available middleware
	 *
	 * @return array<Middleware> Available middleware with slug -> class as key -> value pairs.
	 */
	public function available_middleware(): array {
		return [
			'valid-nonce' => new ValidNonce(),
			'is-allowed'  => new IsAllowed(),
		];
	}
}
