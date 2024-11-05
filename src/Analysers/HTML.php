<?php

namespace Wooping\ShopHealth\Analysers;

use DOMDocument;
use DOMXPath;
use Exception;

/**
 * Loads the HTML of a given page for analysing purposes.
 */
class HTML {

	/**
	 * Holds the url to analyse
	 */
	protected string $url;

	/**
	 * Holds the HTML of the given url.
	 */
	protected DOMDocument $html;

	/**
	 * Does the queries url return a http error.
	 * Anything that is not 200.
	 */
	protected bool $is_error;

	/**
	 * Holds the HTTP status code from the queried url.
	 */
	protected int $status_code;

	/**
	 * Class constructor.
	 *
	 * @throws Exception If the given URL can not be loaded, throw an Exception.
	 */
	public function __construct( string $url ) {
		$this->url = $url;

		try {
			$this->load_url();
		} catch ( Exception $e ) {
			// @ToDo: Catch exception and show nice error message for the user and create a log entry for developers.
		}
	}

	/**
	 * Check whether a URL is actually a valid URL.
	 */
	protected function is_valid_url(): bool {
		return \filter_var( $this->url, \FILTER_VALIDATE_URL );
	}

	/**
	 * Get the HTML from the given URL and store it into a property which can later be called for analysis.
	 *
	 * @throws Exception Throws an excption if not valid.
	 */
	protected function load_url(): void {
		if ( ! $this->is_valid_url() ) {
			throw new Exception(
				\sprintf( '%1$s is not a valid url, please use a different one', \esc_url( $this->url ) )
			);
		}

		/**
		 * Set headers for the get request.
		 * User-Agent: mimic a browser request.
		 */
		$args = [
			'headers' => [
				'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
			],
		];

		/**
		 * Only run this in full debug mode. Otherwise, we _always_ want to verify SSL.
		 */
		if ( \defined( 'WP_DEBUG' ) && \WP_DEBUG && \defined( 'SHOPHEALTH_DEBUG' ) && \SHOPHEALTH_DEBUG ) {
			$args['sslverify'] = false;
		}

		$response = \wp_remote_get( $this->url, $args );

		// Prevent errors from DOMDocument from being shown.
		\libxml_use_internal_errors( true );

		$doc = new DOMDocument();

		if ( \is_wp_error( $response ) ) {
			$error_code    = $response->get_error_code();
			$error_message = $response->get_error_message();
			if ( $error_code === 'http_request_failed' && \strpos( $error_message, 'SSL' ) !== false ) {
				$this->status_code = 495; // Non-standard, but common use: https://http.dev/495.
			} else {
				// Something else went wrong, fallback to 500.
				$this->status_code = 500;
			}
		} else {
			$this->status_code = \wp_remote_retrieve_response_code( $response );
			if ( ! empty( $response ) ) {
				$doc->loadHTML( \wp_remote_retrieve_body( $response ) );
			}
		}

		$this->is_error = $this->status_code !== 200;
		$this->html     = $doc;
	}

	/**
	 * Get the HTML of the given URL DOMXPath. This can be used in validators to check for tags in the HTML.
	 */
	public function analyser(): DOMXPath {
		return new DOMXPath( $this->html );
	}

	/**
	 * Get the HTTP status code of the loaded URL.
	 */
	public function get_status_code(): int {
		return $this->status_code;
	}

	/**
	 * Check whether
	 */
	public function is_error(): bool {
		return $this->is_error;
	}
}
