<?php
/**
 * Plugin Name: Showcase fixture
 * Description: Loads the library the way a real plugin does, for manual and e2e checks.
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/dfx-plugin-showcase/load.php';

add_action(
	'admin_menu',
	static function () {
		add_options_page(
			'Showcase fixture',
			'Showcase fixture',
			'manage_options',
			'showcase-fixture',
			static function () {
				echo '<div class="wrap"><h1>Showcase fixture</h1>';
				echo '<nav class="nav-tab-wrapper"><a class="nav-tab" href="#">General</a><a class="nav-tab nav-tab-active" href="#">' . esc_html( dfx_plugin_showcase_label() ) . '</a></nav>';
				dfx_plugin_showcase_render( array( 'exclude' => 'coupon-audit' ) );
				echo '</div>';
			}
		);
	}
);
