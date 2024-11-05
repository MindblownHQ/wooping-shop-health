<?php

namespace Wooping\ShopHealth\WordPress;

use Illuminate\Database\QueryException;
use Wooping\ShopHealth\Contracts\Interfaces\Hookable;
use Wooping\ShopHealth\Contracts\Router;
use Wooping\ShopHealth\Models\Issue;

/**
 * Class RegisterMenu
 *
 * Registers the Wooping Menu
 */
class RegisterPages extends Router implements Hookable {

	/**
	 * Routes of this plugin
	 *
	 * @var array|string[]
	 */
	protected array $routes;

	/**
	 * Registers the hooks for the menu items in the admin menu.
	 */
	public function register_hooks(): void {

		// set the actions to hook into.
		\add_action( 'admin_menu', [ $this, 'init' ], 100 );
		\add_action( 'admin_menu', [ $this, 'set_subpages' ], 200 );
		\add_filter( 'submenu_file', [ $this, 'set_current_menu_item' ] );
	}

	/**
	 * Register all menu routes for this application
	 */
	public function init(): void {

		global $menu;

		// fetch all admin routes.
		$this->get_routes( 'admin' );

		try {
			$issue_count = Issue::where( 'status', 'open' )->count();
		} catch ( QueryException $e ) {
			$issue_count = 0;
		}

		$svg_icon = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzEyIiBoZWlnaHQ9IjMwMSIgdmlld0JveD0iMCAwIDMxMiAzMDEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGZpbGw9IiNhN2FhYWQiIGQ9Ik0xOTkuNTExIDI4My44MzVDMTk5LjUxMSAyOTMuMzA1IDE5MS45MzYgMzAwLjk3NiAxODIuNTg1IDMwMC45NzZDMTczLjIzNSAzMDAuOTc2IDE2NS42NiAyOTMuMzA1IDE2NS42NiAyODMuODM1QzE2NS42NiAyNzQuMzY2IDE3My4yMzUgMjY2LjY5NSAxODIuNTg1IDI2Ni42OTVDMTkxLjkzNiAyNjYuNjk1IDE5OS41MTEgMjc0LjM2NiAxOTkuNTExIDI4My44MzVaTTMxMS45OTkgMTQuNTQ3MUwzMTEuMjg5IDc2LjkwMDdDMzExLjIxOCA4Mi40MTQ1IDMwOC4xNDEgODcuMzA1IDMwMy4yNDEgODkuNzAyM0MyOTguMzQxIDkyLjA3NTYgMjkyLjYxMiA5MS40NTIzIDI4OC4zNTEgODguMDQ4MkwyODQuNDkyIDg0Ljk3OTZMMjM1LjkxOCAxOTUuNTY3QzIzNC4wNDggMTk5LjgzNCAyMjkuODU4IDIwMi41OTEgMjI1LjI0MiAyMDIuNTkxSDEyOS45NjNDMTI4LjYzNyAyMDQuNDM3IDEyNy4yNDEgMjA2LjIxMSAxMjUuNzk3IDIwNy44NDFDMTE3LjI1MSAyMTcuNTAyIDEwNy4zMDkgMjIyLjIyNSA5NS40NDk2IDIyMi4yMjVDOTQuNjY4NCAyMjIuMjI1IDkzLjkxMDkgMjIyLjIyNSA5My4xMDYxIDIyMi4xNzdDOTIuMDg4MiAyMjIuMTI5IDkxLjA0NjYgMjIyLjAwOSA5MC4wMjg3IDIyMS44ODlMODMuODAzIDIzNS41M0gyMDIuNzU0QzIwOS4yMTYgMjM1LjUzIDIxNC40MjQgMjQwLjgyOCAyMTQuNDI0IDI0Ny4zNDlDMjE0LjQyNCAyNTMuODY5IDIwOS4xOTMgMjU5LjE2NyAyMDIuNzU0IDI1OS4xNjdINjUuNTA0N0M2MS41Mjc5IDI1OS4xNjcgNTcuODExNCAyNTcuMTA2IDU1LjY1NzMgMjUzLjcwMUM1My41MDMxIDI1MC4yOTcgNTMuMjE5MSAyNDYuMDMgNTQuODk5OCAyNDIuMzYyTDY4LjgxODggMjExLjk2NEM2OC4wODUgMjExLjI5MyA2Ny4zNzQ4IDIxMC41NzQgNjYuNjY0NyAyMDkuODU1QzU2LjAzNiAxOTguNjM1IDQ4Ljg4NzEgMTgwLjQ0IDQ1LjQzMTEgMTU1LjcyNEM0My4zOTUzIDE0MS4xOTYgNDIuODAzNSAxMjUuMDYyIDQzLjY1NTcgMTA5LjgxNkwzMi41NzczIDExNC44MDJDMjkuNTk0NiAxMTYuMTQ0IDI2LjQyMjYgMTE2Ljg0IDIzLjE3OTYgMTE2Ljg0QzE0LjAxODYgMTE2Ljg0IDUuNzA5NzkgMTExLjM3NCAxLjk5MzMyIDEwMi44ODdDLTMuMTkwOCA5MS4wNjg4IDIuMDg4MDEgNzcuMTY0NCAxMy43NTgyIDcxLjg5MDRMNjkuMzM5NiA0Ni44NjI2QzcxLjM5OSA0NS45Mjc3IDczLjU3NjggNDUuMzI4NCA3NS44MjU2IDQ1LjA0MDdDODguMDg3NiA0MS41ODg2IDEwMS4zNjggNDUuMjMyNSAxMDkuMDYxIDU0LjI3MDNDMTE1LjQwNSA2MS43MDE5IDExNy41MTIgNzIuMjI2IDExNy45ODUgODIuNTU4NEMxMjYuMzg5IDcwLjcxNTcgMTM3LjQ5MSA1Ni44ODMzIDE0OC45OTUgNDcuOTE3NEwxNDkuMTE0IDQ3LjgyMTVDMTYyLjY3NyAzNy42NTcgMTc3Ljg1MSAzNC45NDgxIDE4OS42ODcgNDAuNjA1N0MxOTYuNTI4IDQzLjg2NiAyMDguMTI3IDUyLjg3OTggMjA5Ljg1NSA3Ni42MzdDMjExLjkxNSA3NC4zMTE3IDIxNC4xMTYgNzEuODkwNCAyMTYuNDYgNjkuNDIxMkMyMjIuMDk0IDYzLjQyNzkgMjI5Ljk3NiA1NS41MTY5IDIzOC41MjIgNDguMzI1TDIyOS40NTYgNDEuMTA5MUMyMjUuMTcxIDM3LjcwNSAyMjMuMjA2IDMyLjIzOTEgMjI0LjM0MiAyNi44NDUyQzIyNS40NzkgMjEuNDUxMyAyMjkuNDMyIDE3LjI1NiAyMzQuNzExIDE1Ljg4OTZMMjk0LjI5MyAwLjQ1MDk3MUMyOTguNjAxIC0wLjY1MTc4NSAzMDMuMDc1IDAuMjgzMTYgMzA2LjU3OCAzLjA4OEMzMTAuMDgyIDUuODY4ODYgMzEyLjA0NyAxMC4wNDAyIDMxMS45OTkgMTQuNTQ3MVpNMjk2LjM5OSAxNi4xNzcyTDI0MS4yMiAzMC40NDExTDI2My45NjkgNDguNTQwN0wyNTUuNjYgNTQuNjc3OEMyNDUuMTAzIDYyLjQ2OSAyMzQuNjE2IDcyLjg5NzMgMjI3LjY4IDgwLjI1N0MyMTcuODA5IDkwLjczMzEgMjEwLjM3NiAxMDAuMDU5IDIwNi45OTEgMTA0Ljg1M0wxODcuMzY3IDEzMi43MzRMMTkyLjk3NyA5OC45NTU5QzE5NC4yMDggOTEuNTk2MiAxOTQuNjU4IDg0LjgzNTggMTk0LjM1IDc4LjkxNDVDMTkzLjY4OCA2Ni41NDQ0IDE4OS42NjMgNTcuOTg2MSAxODMuMDEyIDU0LjgyMTZDMTc2LjQ1NCA1MS43MDUyIDE2Ny4yNDYgNTMuNzkwOCAxNTguMzkzIDYwLjQwNzNDMTQ4Ljk0OCA2Ny43OTEgMTM3LjUxNCA4MS4xOTE5IDEyNi4xNzYgOTguMDkyOEMxMjIuMzY0IDEwMy43NzQgMTE4LjY5NSAxMDkuNjk2IDExNS41NDcgMTE1LjIxTDk3Ljk1ODggMTQ2LjA2M0wxMDEuMjQ5IDEwOC4wNjZDMTAxLjYwNCAxMDQuMDg2IDEwMi4wMDcgOTkuNTU1MiAxMDIuMjQzIDk1LjAyNDNDMTAyLjUyNyA4OS4yNzA4IDEwMy40MjcgNzEuNzcwNSA5Ny4yMDEzIDY0LjQ4MjdDOTMuMzY2NSA1OS45NzU4IDg2LjA5OTIgNTguMjQ5OCA3OS41NDIxIDYwLjI2MzVMNzguNzg0NiA2MC41MDMyTDc4LjAwMzUgNjAuNTc1MkM3Ny4xNzQ5IDYwLjY0NzEgNzYuMzcwMSA2MC44NjI4IDc1LjYxMjYgNjEuMTk4NUwyMC4wMzEyIDg2LjIyNjJDMTguMTg0OCA4Ny4wNjUzIDE2Ljc0MDkgODguNTc1NiAxNi4wMzA3IDkwLjQ5MzRDMTUuMjk2OSA5Mi40MTEzIDE1LjM0NDIgOTQuNDk2OSAxNi4xNzI3IDk2LjM5MDhDMTcuNDAzNyA5OS4xNzE2IDIwLjEyNTkgMTAwLjk3IDIzLjEzMjIgMTAwLjk3QzI0LjE5NzUgMTAwLjk3IDI1LjIzOSAxMDAuNzU0IDI2LjIwOTYgMTAwLjI5OEw2MS45MzAzIDg0LjIxMjVMNjAuMjczMyA5OC4wMjA5QzU2LjAxMjQgMTMzLjM4MSA2MC43MjMgMTgwLjcyOCA3Ny44ODUxIDE5OC44MjdDODEuMTc1NSAyMDIuMzAzIDg0LjcwMjYgMjA0LjUzMyA4OC42Nzk0IDIwNS42NkM5MC40NTQ4IDIwNS45OTUgOTIuMjA2NSAyMDYuMjM1IDkzLjg4NzIgMjA2LjMwN0MxMDIuMDA3IDIwNi43MTQgMTA4LjI1NiAyMDMuOTA5IDExNC4xNzQgMTk3LjIyMUMxMjEuMTU3IDE4OS4zMzQgMTI2Ljg2MiAxNzYuODQ0IDEzMi44OTggMTYzLjYzNUMxMzcuODIyIDE1Mi44NzEgMTQyLjkxMiAxNDEuNzQ4IDE0OS40OTIgMTMwLjg4OEwxNjYuNDE4IDEwMy4wMDdMMTYzLjg4NSAxMzUuNjM1QzE2MS45NDQgMTYwLjgwNiAxNjguMjE3IDE3OS4wNSAxODAuNzE1IDE4NC40MTlDMTg4LjY2OSAxODcuODQ4IDE5OC4wNjcgMTg1LjE4NyAyMDQuNjk1IDE3Ny42NTlMMjA1LjA1IDE3Ny4yNzZDMjA5LjI0IDE3My4xMDQgMjEzLjMxMSAxNjQuNjE4IDIxOC45OTMgMTUyLjg0N0MyMjQuODQgMTQwLjY5MyAyMzIuMTA3IDEyNS41NjYgMjQzLjIzMyAxMDcuMjc0QzI1MC4zODEgOTUuMzM2IDI1OC4yODggODQuODExOCAyNjUuMjcxIDc1LjUzNDNDMjY3Ljg5OSA3Mi4wMzQyIDI3MC4zNiA2OC43NDk5IDI3Mi41NjIgNjUuNzA1NEwyNzcuMzIgNTkuMDg4OEwyOTUuNzYgNzMuNzYwM0wyOTYuNDIzIDE2LjA4MTNMMjk2LjM5OSAxNi4xNzcyWk03Ni43MDE1IDI2Ni43MTlDNjcuMzUxMSAyNjYuNzE5IDU5Ljc3NjIgMjc0LjM5IDU5Ljc3NjIgMjgzLjg1OUM1OS43NzYyIDI5My4zMjkgNjcuMzUxMSAzMDEgNzYuNzAxNSAzMDFDODYuMDUxOSAzMDEgOTMuNjI2OCAyOTMuMzI5IDkzLjYyNjggMjgzLjg1OUM5My42MjY4IDI3NC4zOSA4Ni4wNTE5IDI2Ni43MTkgNzYuNzAxNSAyNjYuNzE5WiIvPgo8L3N2Zz4K';

		// always set the leading page.
		$dashboard = $this->routes['get']['wooping'];

		$screen_id = \add_menu_page(
			$dashboard['label'],
			$dashboard['label'],
			'manage_woocommerce',
			'woop_dashboard',
			false,
			$svg_icon
		);

		\wc_admin_connect_page(
			[
				'id'        => 'woop_dashboard',
				'screen_id' => $screen_id,
				'title'     => 'Wooping',
				'path'      => '',
			]
		);

		// Override the menu title to add the amount of issues
		// This is done later, as the label is used to generate screen ID's.
		foreach ( $menu as $index => $menu_item ) {
			if ( $menu_item[2] === 'woop_dashboard' ) {
				$menu[ $index ][0] = \sprintf( '%1$s <span class="sh-issue-count awaiting-mod count-%2$d"><span class="plugin-count">%2$d</span></span>', $dashboard['label'], $issue_count ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}
		}

		// remove it from the routes, as we are now only adding sub pages.
		unset( $this->routes['get']['wooping'] );

		// then, loop through all regular get route options.
		foreach ( $this->routes['get'] as $key => $route ) {

			// only select the ones that are supposed to show up in the menu.
			if ( isset( $route['location'] ) && $route['location'] === 'menu' ) {

				$screen_id = \add_submenu_page(
					'woop_dashboard',
					$route['label'],
					$route['label'],
					'manage_woocommerce',
					'woop_' . $key,
					$this->get_trigger( $route )
				);

				\wc_admin_connect_page(
					[
						'id'        => $screen_id,
						'screen_id' => $screen_id,
						'title'     => $route['label'],
					]
				);

			}
		}
	}

	/**
	 * Set the submenu pages
	 */
	public function set_subpages(): void {

		global $submenu;

		foreach ( $this->routes['get'] as $key => $route ) {
			if ( isset( $route['location'] ) && $route['location'] === 'shop-health' ) {

				$screen_id = \add_submenu_page(
					'woop_dashboard',
					$route['label'],
					$route['label'],
					'manage_woocommerce',
					'woop_' . $key,
					$this->get_trigger( $route )
				);

				\wc_admin_connect_page(
					[
						'id'        => $screen_id,
						'screen_id' => $screen_id,
						'parent'    => 'woop_dashboard',
						'title'     => [ 'Wooping', $route['label'] ],
						'path'      => \add_query_arg( 'page', 'woop_' . $key, 'admin.php' ),
					]
				);
			}

			// Mark the item as hidden.
			if ( isset( $route['display'] ) && $route['display'] === 'hidden' ) {
				foreach ( $submenu['woop_dashboard'] as &$item ) {
					if ( $item[2] === "woop_{$key}" ) {
						$item[4] = ( $item[4] ?? '' ) . ' hidden';
					}
				}
			}
		}
	}

	/**
	 * Set the current menu item when viewing a Shop Health Tab
	 *
	 * @param ?string $file The menu item slug.
	 * @return ?string The menu item slug.
	 */
	public function set_current_menu_item( ?string $file ): ?string {
		$screen = \get_current_screen();
		$this->get_routes( 'admin' );
		foreach ( $this->routes['get'] as $key => $route ) {
			if ( isset( $route['location'] ) && $route['location'] === 'shop-health' && \strpos( $screen->id, $key ) ) {
				$file = 'woop_dashboard';
			}
		}

		return $file;
	}
}
