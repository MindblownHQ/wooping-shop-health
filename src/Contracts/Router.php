<?php

namespace Wooping\ShopHealth\Contracts;

use Error;

/**
 * Class Router
 *
 * A router fetches defined routes from routes.php
 * and finds and validates the trigger for that route (a method in a controller).
 */
abstract class Router {

	/**
	 * Routes of this plugin
	 *
	 * @var array<array|string> $routes is a multidimensional array of strings defined in src/routes.php
	 */
	protected array $routes;

	/**
	 * Returns the routes as an array, possibly of a precise key
	 *
	 * @return array<array|string> $routes
	 */
	public function get_routes( ?string $key = null ): array {

		// return the local variable if we've set it already.
		if ( empty( $this->routes ) === false ) {
			return $this->routes;
		}

		// fetch the routes registered in routes.php.
		$routes = \wooping_get_routes();

		// possibly drill down to the key of the array we're requesting.
		if ( ! \is_null( $key ) && isset( $routes[ $key ] ) ) {
			$routes = $routes[ $key ];
		}

		// save as a local variable and return.
		$this->routes = $routes;
		return $routes;
	}

	/**
	 * Return a single route
	 *
	 * @return string|array<array|string> $route
	 */
	public function get_route( string $name, string $category ) {

		$name = \str_replace( 'woop_', '', $name );
		if ( isset( $this->routes[ $category ][ $name ] ) ) {
			return $this->routes[ $category ][ $name ]; // get the route.
		}

		return null;
	}

	/**
	 * Returns the controller and method to trigger of a router endpoint.
	 *
	 * @throws Error If a route doesn't have a valid trigger.
	 * @throws Error If a method called on a controller doesn't exist.
	 *
	 * @return array<string> A call_user_func_array compatible array of Class and method.
	 */
	public function get_trigger( ?array $route ): array {

		// return nulled routes gracefully.
		if ( \is_null( $route ) ) {
			return [];
		}

		// set the trigger.
		$trigger = ( isset( $route['triggers'] ) ? $route['triggers'] : [] );

		// check if this route is added well.
		if ( empty( $trigger ) || ! \is_array( $trigger ) ) {
			throw new Error( 'A valid route needs to have a Controller class and a method defined with the triggers key as [Controller::class, "method"].' );
		}

		// set class and method as seperate variables.
		$instance = new $trigger[0]();
		$method   = $trigger[1];

		// test if this method exists on this controller.
		if ( ! \method_exists( $instance, $method ) ) {
			throw new Error(
				\sprintf(
					\esc_html( 'Method %1$s on controller %2$s not found' ),
					\esc_html( $method ),
					\esc_html( $instance )
				)
			);
		}

		return [ $instance, $method ]; // return the initiated controller and its method.
	}
}
