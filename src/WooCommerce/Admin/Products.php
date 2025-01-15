<?php

namespace Wooping\ShopHealth\WooCommerce\Admin;

use WC_Product;
use Wooping\ShopHealth\Contracts\Interfaces\Hookable;
use Wooping\ShopHealth\Models\Database\Options;
use Wooping\ShopHealth\Models\ScannedObject;
use Wooping\ShopHealth\Models\Schema\AddScannedObjectsTable;
use Wooping\ShopHealth\Queue\ScanProduct;
use WP_Post;

/**
 * Class Products
 *
 * Manages the Product admin screens.
 */
class Products implements Hookable {

	/**
	 * Registers WordPress action and filter hooks.
	 *
	 * @return void
	 */
	public function register_hooks(): void {

		\add_filter( 'manage_product_posts_columns', [ $this, 'add_product_health_column' ], 10 );
		\add_action( 'manage_product_posts_custom_column', [ $this, 'product_health' ], 10, 2 );
		\add_action( 'add_meta_boxes', [ $this, 'add_health_meta_box' ], 10 );
		\add_action( 'wp_trash_post', [ $this, 'delete_scanned_object' ], 10 );
		\add_action( 'delete_post', [ $this, 'delete_scanned_object' ], 10 );
		\add_action( 'woocommerce_update_product', [ $this, 'scan_after_save' ], 10, 2 );
		\add_action( 'post_submitbox_misc_actions', [ $this, 'show_product_score' ] );
	}

	/**
	 * Add product health column to the dashboard
	 *
	 * @param array<string> $columns An associative array of column names and labels.
	 *
	 * @return array<string> The updated columns array with the added product health column.
	 */
	public function add_product_health_column( array $columns ): array {
		$columns['product_health'] = \__( 'Health', 'wooping-shop-health' );

		return $columns;
	}

	/**
	 * Show Product Score
	 *
	 * @param WP_Post $post The post object.
	 */
	public function show_product_score( $post ) {

		if ( $post->post_type !== 'product' ) {
			return;
		}

		// Check if the Shop Health database tables exist first, before hooking into anything.
		if ( ( new AddScannedObjectsTable() )->exists() === false ) {
			return;
		}

		$score = ScannedObject::where( 'object_id', $post->ID )
								->with( [ 'relevant_issues' ] )
								->pluck( 'score' )
								->first();

		?>
		<div class="misc-pub-section" id="sh-product-score">
			<a href="#wooping-shop-health-issues subsublist"
				style="display: flex; align-items: center; gap: 0.17rem; color: black; text-decoration: none;">
				<svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
					<g clip-path="url(#clip0_550_702)">
						<path
							d="M14.7075 20.9542C14.7075 21.6532 14.1491 22.2196 13.4598 22.2196C12.7705 22.2196 12.2121 21.6532 12.2121 20.9542C12.2121 20.2551 12.7705 19.6888 13.4598 19.6888C14.1491 19.6888 14.7075 20.2551 14.7075 20.9542ZM22.9999 1.07394L22.9476 5.67721C22.9424 6.08426 22.7155 6.4453 22.3543 6.62228C21.9931 6.79749 21.5708 6.75148 21.2566 6.50016L20.9722 6.27363L17.3914 14.4377C17.2535 14.7528 16.9447 14.9563 16.6044 14.9563H9.58061C9.48289 15.0926 9.37993 15.2235 9.27348 15.3439C8.64352 16.0571 7.91061 16.4058 7.03635 16.4058C6.97876 16.4058 6.92292 16.4058 6.86359 16.4022C6.78855 16.3987 6.71177 16.3898 6.63673 16.381L6.17779 17.388H14.9466C15.423 17.388 15.8069 17.7791 15.8069 18.2605C15.8069 18.7419 15.4212 19.133 14.9466 19.133H4.82888C4.53571 19.133 4.26174 18.9808 4.10294 18.7295C3.94414 18.4782 3.9232 18.1632 4.0471 17.8924L5.07318 15.6483C5.01908 15.5987 4.96673 15.5456 4.91438 15.4926C4.13086 14.6643 3.60386 13.321 3.34908 11.4963C3.19901 10.4238 3.15539 9.23275 3.21821 8.10715L2.40153 8.47527C2.18166 8.57438 1.94782 8.6257 1.70875 8.6257C1.03342 8.6257 0.420914 8.22219 0.146944 7.59567C-0.235219 6.72316 0.153924 5.69667 1.01423 5.30732L5.11157 3.45964C5.26339 3.39062 5.42393 3.34637 5.58971 3.32513C6.49364 3.07028 7.47261 3.33929 8.03974 4.00651C8.50741 4.55515 8.66272 5.33209 8.69762 6.09488C9.31711 5.22059 10.1355 4.19942 10.9836 3.53751L10.9923 3.53043C11.9922 2.78003 13.1108 2.58004 13.9833 2.99772C14.4877 3.23841 15.3427 3.90386 15.4701 5.65774C15.6219 5.48607 15.7842 5.30732 15.957 5.12503C16.3723 4.68257 16.9534 4.09854 17.5833 3.5676L16.915 3.03488C16.5991 2.78357 16.4543 2.38006 16.5381 1.98185C16.6218 1.58364 16.9132 1.27393 17.3024 1.17305L21.6946 0.033293C22.0122 -0.0481181 22.3421 0.0209043 22.6003 0.227972C22.8586 0.433269 23.0034 0.741216 22.9999 1.07394ZM21.85 1.19429L17.7823 2.24732L19.4593 3.58352L18.8467 4.03659C18.0685 4.61178 17.2954 5.38165 16.7841 5.92498C16.0564 6.69838 15.5085 7.38684 15.259 7.7408L13.8123 9.79908L14.2259 7.30543C14.3166 6.7621 14.3498 6.26301 14.3271 5.82587C14.2782 4.91265 13.9816 4.28083 13.4912 4.04721C13.0079 3.81714 12.329 3.97111 11.6764 4.45958C10.9801 5.00468 10.1373 5.994 9.3014 7.24171C9.02045 7.66116 8.74997 8.0983 8.51788 8.50536L7.22132 10.7831L7.46388 7.97795C7.49006 7.68417 7.51972 7.34967 7.53717 7.01518C7.55811 6.59043 7.62442 5.29847 7.16548 4.76045C6.88278 4.42772 6.34706 4.3003 5.86368 4.44896L5.80784 4.46666L5.75025 4.47197C5.68918 4.47728 5.62985 4.4932 5.57401 4.51798L1.47666 6.36566C1.34055 6.4276 1.2341 6.5391 1.18175 6.68069C1.12765 6.82227 1.13114 6.97624 1.19222 7.11606C1.28296 7.32135 1.48364 7.45409 1.70526 7.45409C1.78379 7.45409 1.86057 7.43816 1.93212 7.40454L4.56538 6.217L4.44322 7.2364C4.12912 9.84687 4.47638 13.3422 5.74153 14.6784C5.98409 14.9351 6.2441 15.0997 6.53727 15.1828C6.66814 15.2076 6.79728 15.2253 6.92117 15.2306C7.51972 15.2607 7.98041 15.0536 8.41667 14.5599C8.93146 13.9776 9.35201 13.0555 9.79699 12.0804C10.16 11.2857 10.5351 10.4645 11.0203 9.66281L12.268 7.60452L12.0812 10.0132C11.9382 11.8715 12.4006 13.2183 13.322 13.6148C13.9083 13.8679 14.6011 13.6714 15.0897 13.1157L15.1159 13.0874C15.4247 12.7794 15.7249 12.1529 16.1437 11.284C16.5747 10.3867 17.1104 9.26991 17.9306 7.91955C18.4576 7.03819 19.0404 6.26124 19.5552 5.57633C19.7489 5.31793 19.9304 5.07547 20.0927 4.85071L20.4435 4.36224L21.8028 5.44536L21.8517 1.18721L21.85 1.19429ZM5.65428 19.6905C4.96499 19.6905 4.40658 20.2569 4.40658 20.9559C4.40658 21.655 4.96499 22.2214 5.65428 22.2214C6.34357 22.2214 6.90198 21.655 6.90198 20.9559C6.90198 20.2569 6.34357 19.6905 5.65428 19.6905Z"
							fill="#8C8F94"/>
					</g>
					<defs>
						<clipPath id="clip0_550_702">
							<rect width="23" height="22.2214" fill="white"/>
						</clipPath>
					</defs>
				</svg>
				<?php \esc_html_e( 'Product Health:', 'wooping-shop-health' ); ?>
				<strong id="sh-product-score-display">
					<span><?php echo \esc_html( $score ); ?> </span>
				</strong>
			</a>
		</div>
		<?php
	}

	/**
	 * Returns the HTML code for displaying the product health based on a random value.
	 */
	public function product_health( string $column, int $post_id ): void {
		if ( $column === 'product_health' ) {
			$object = ScannedObject::where( 'object_id', $post_id )
					->with( [ 'relevant_issues' ] )
					->first();
			if ( ! \is_null( $object ) ) {
				echo '<div class="wsh-pill wsh-pill__score" style="--score: ' . \esc_html( $object->score ) . '">';
				echo '<span>' . \esc_html( $object->score ) . '</span>';
				echo '</div>';
			}
		}
	}

	/**
	 * Register the Product Health Metabox
	 *
	 * @return void
	 */
	public function add_health_meta_box() {

		// Check if the Shop Health database tables exist first, before hooking into anything.
		if ( ( new AddScannedObjectsTable() )->exists() === false ) {
			return;
		}

		\add_meta_box(
			'wooping_product_health_issues_metabox',
			\__( 'Wooping Product Health Issues', 'wooping-shop-health' ),
			[ $this, 'health_metabox_issues_content' ],
			'product',
			'normal',
			'default'
		);
	}

	/**
	 * Displays the issues content for the health metabox
	 *
	 * @param WP_Post $post The post object.
	 *
	 * @return void
	 */
	public function health_metabox_issues_content( WP_Post $post ): void {
		$object = ScannedObject::where( 'object_id', $post->ID )
								->with( [ 'relevant_issues' ] )
								->first();
		if ( ! \is_null( $object ) ) {
			\woop_template( 'metaboxes.product-issues', \compact( 'object' ) );
		}else {
			\woop_template( 'metaboxes.empty-state' );
		}
	}

	/**
	 * Run a product scan on the save product hook.
	 */
	public function scan_after_save( int $product_id, WC_Product $product ): void {
		// Do not run on auto-saves.
		if ( \wp_is_post_autosave( $product_id ) ) {
			return;
		}

		// Do not run for revisions.
		if ( \wp_is_post_revision( $product_id ) ) {
			return;
		}

		// Do not run for auto-drafts and trash.
		if ( \in_array( $product->get_status(), [ 'trash', 'auto-draft' ], true ) ) {
			return;
		}

		// add timestamp here, so our progress bar can query on a certain timestamp.
		( new Options() )->set_queue_timestamp();

		( new ScanProduct() )->scan( $product_id );
	}

	/**
	 * Remove the scanned object.
	 *
	 * Triggered on wp_delete_post and wp_trash_post.
	 * Includes a get_post_type check which returns false if no post was found.
	 */
	public function delete_scanned_object( int $post_id ): void {

		if ( \get_post_type( $post_id ) !== 'product' ) {
			return;
		}

		$objects = ScannedObject::where( 'object_id', $post_id )
				->with( [ 'issues' ] )
					->get();

		// Loop through the objects.
		foreach ( $objects as $object ) {

			// Delete issues.
			if ( $object && $object->issues ) {
				foreach ( $object->issues as $issue ) {
					$issue->delete();
				}
			}

			// Delete the scanned object.
			$object->delete();
		}
	}
}
