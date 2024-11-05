<?php

namespace Wooping\ShopHealth\Contracts\Interfaces;

interface Middleware {

	/**
	 * The handle method performs the actual logic of this middleware.
	 * Upon fail, it will either redirect or throw an error.
	 *
	 * Otherwise, it will always return true.
	 */
	public function handle(): bool;
}
