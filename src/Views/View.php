<?php

namespace Wooping\ShopHealth\Views;

/**
 * Class View
 *
 * A base class that provides a basic structure for creating views.
 * Views are endpoints that require rendering.
 */
class View {

	protected string $template_name = '';

	/**
	 * Holds the variables that will to be passed to the view.
	 *
	 * @var array<string|array> $attributes
	 */
	protected array $attributes = [];

	/**
	 * Populate this view
	 *
 	 * @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
	 */
	public function set( string $template_name, array $attributes = [] ): View {
		$this->template_name = $template_name;
		$this->attributes    = $attributes;

		return $this;
	}

	/**
	 * Render this view
	 */
	public function render() {

		$template_name = $this->template();
		$attributes    = $this->get_attributes();

		if ( \is_null( $template_name ) ) {
			echo '<p>File not found: ' . \esc_html( $this->template_name ) . '.php</p>';
			die();
		}

		// Extract args if there are any.
		if ( \count( $attributes ) > 0 ) {
			// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
			\extract( $attributes );
		}

		require $template_name;
	}

	/**
	 * Returns a template file
	 */
	public function template(): ?string {
		$file = \SHOP_HEALTH_PATH . '/templates/' . $this->template_name . '.php';
		if ( \file_exists( $file ) ) {
			return $file;
		}

		return null;
	}

	/**
	 * Get all data to send to the view
	 *
	 * @return array<array|string|float|int> Attributes for this view.
	 */
	public function get_attributes(): array {
		// this array is unique for each view.
		$attributes = $this->attributes;

		// always load the defaults passed by the "set" function.
		$attributes = \wp_parse_args( $attributes, $this->attributes );

		// always set errors and notifications.
		if ( \method_exists( $this, 'errors' ) ) {
			$attributes['errors'] = $this->errors();
		}
		if ( \method_exists( $this, 'notifications' ) ) {
			$attributes['notifications'] = $this->notifications();
		}

		// return full data array.
		return $attributes;
	}
}
