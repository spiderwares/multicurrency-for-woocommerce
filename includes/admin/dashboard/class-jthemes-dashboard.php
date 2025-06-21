<?php
/**
 * Jthemes Dashboard Class
 *
 * Handles the admin dashboard setup and related functionalities.
 *
 * @package Jthemes
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Jthemes_Dashboard' ) ) {

	/**
	 * Class Jthemes_Dashboard
	 *
	 * Initializes the admin dashboard for Jthemes.
	 */
	class Jthemes_Dashboard {

		/**
		 * Constructor for Jthemes_Dashboard class.
		 * Initializes the event handler.
		 */
		public function __construct() {
			$this->events_handler();
		}

		/**
		 * Initialize hooks for admin functionality.
		 */
		private function events_handler() {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		}

		/**
		 * Enqueue admin-specific styles for the dashboard.
		 */
		public function enqueue_scripts() {
			wp_enqueue_script('jquery-ui-sortable');

			// Enqueue the select 2 js.
			wp_enqueue_script(
				'select-2',
				MCWC_URL . '/assets/js/select2.js',
				array( ), // Dependencies
				MCWC_VERSION,
				true // Load in footer
			);

			// Enqueue the select 2 CSS.
			wp_enqueue_style(
				'select-2',
				MCWC_URL . '/assets/css/select2.min.css',
				[],
				MCWC_VERSION // Version number for cache-busting.
			);

			// Enqueue the Jthemes dashboard CSS.
			wp_enqueue_style(
				'jthemes-dashboard',
				MCWC_URL . '/assets/css/admin-styles.css',
				[],
				MCWC_VERSION // Version number for cache-busting.
			);

			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script(
				'mcwc-admin-js',
				MCWC_URL . '/assets/js/mcwc-admin.js',
				array( 'jquery', 'wp-color-picker', 'jquery-ui-sortable', 'select-2' ), // Dependencies
				MCWC_VERSION,
				true // Load in footer
			);

			wp_localize_script('mcwc-admin-js', 'mcwcParams', [
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce'    => wp_create_nonce('mcwc_currency_nonce'),
			]);
		}

		/**
		 * Add Jthemes menu and submenu to the WordPress admin menu.
		 */
		public function admin_menu() {
			// Add the main menu page.
			add_menu_page(
				'Jthemes', 					   // Page title.
				'Jthemes', 					   // Menu title.
				'manage_options',                  // Capability required to access.
				'jthemes',                     // Menu slug.
				[ $this, 'dashboard_callback' ],   // Callback function to render content.
				'data:image/svg+xml;base64,' . base64_encode( file_get_contents( MCWC_PATH . 'assets/img/admin/jthemes.svg' ) ), // Icon URL.
				26                                 // Position in the menu.
			);

			// Add a submenu page under the main Jthemes menu.
			add_submenu_page(
				'jthemes',                     // Parent slug.
				'Jthemes About', 			   // Page title.
				'About',      					   // Menu title.
				'manage_options',                  // Capability required to access.
				'jthemes'                      // Menu slug.
			);
		}

		/**
		 * Callback function for rendering the dashboard content.
		 */
		public function dashboard_callback() {
			// Include the about page view file.
			require_once MCWC_PATH . 'includes/admin/dashboard/views/about.php';
		}
	}

	// Instantiate the Jthemes_Dashboard class.
	new Jthemes_Dashboard();
}
