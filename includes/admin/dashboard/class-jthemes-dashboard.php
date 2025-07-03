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

if ( ! class_exists( 'MCWC_Jthemes_Dashboard' ) ) {

	/**
	 * Class MCWC_Jthemes_Dashboard
	 *
	 * Initializes the admin dashboard for Jthemes.
	 */
	class MCWC_Jthemes_Dashboard {

		/**
		 * Constructor for MCWC_Jthemes_Dashboard class.
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
			add_action( 'wp_ajax_jthemes_get_plugins_kit', [ $this, 'ajax_get_plugins_kit' ] );
		}

		/**
		 * Enqueue admin-specific styles for the dashboard.
		 */
		public function enqueue_scripts() {

			wp_enqueue_script( 'thickbox' );
        	wp_enqueue_style( 'thickbox' );

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

			wp_enqueue_style(
				'jthemes-dashboard',
				MCWC_URL . 'includes/admin/dashboard/css/jthemes-dashboard.css',
				[],
				MCWC_VERSION // Version number for cache-busting.
			);

			wp_enqueue_script(
				'jthemes-dashboard', 
				MCWC_URL . 'includes/admin/dashboard/js/jthemes-dashboard.js', 
				array(), 
				MCWC_VERSION, 
				[ 'in_footer' => true ]
			);

			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script(
				'mcwc-admin-js',
				MCWC_URL . '/assets/js/mcwc-admin.js',
				array( 'jquery', 'wp-color-picker', 'jquery-ui-sortable', 'select-2' ), // Dependencies
				MCWC_VERSION,
				true // Load in footer
			);

			wp_localize_script( 'mcwc-admin-js', 'mcwcParams', [
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
				esc_html__( 'Jthemes', 'multicurrency-for-woocommerce' ),
				esc_html__( 'Jthemes', 'multicurrency-for-woocommerce' ),
				'manage_options',                  // Capability required to access.
				'mcwc_jthemes',                     		// Menu slug.
				[ $this, 'dashboard_callback' ],   // Callback function to render content.
				'data:image/svg+xml;base64,' . base64_encode( file_get_contents( MCWC_PATH . 'assets/img/admin/jthemes.svg' ) ), // Icon URL.
				26                                 // Position in the menu.
			);

			// Add a submenu page under the main Jthemes menu.
			add_submenu_page(
				'mcwc_jthemes',                     // Parent slug.
				'Jthemes About', 			   // Page title.
				'About',      				   // Menu title.
				'manage_options',              // Capability required to access.
				'mcwc_jthemes'                      // Menu slug.
			);
		}

		/**
		 * Callback function for rendering the dashboard content.
		 */
		public function dashboard_callback() {
			// Include the about page view file.
			require_once MCWC_PATH . 'includes/admin/dashboard/views/about.php';
		}

		/**
		 * AJAX callback to fetch and display Spiderwares plugin kit.
		 *
		 * @return void
		 */
		public function ajax_get_plugins_kit() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'mcwc_currency_nonce' ) ) :
                return;
            endif;

			$transient_key = 'jthemesstudio_plugins_kit';

			// Attempt to retrieve plugin data from cache.
			if ( false === ( $plugins = get_transient( $transient_key ) ) ) :
				$args = (object) [
					'author'   => 'jthemesstudio',
					'per_page' => 120,
					'page'     => 1,
					'fields'   => [
						'slug',
						'name',
						'version',
						'downloaded',
						'active_installs',
						'last_updated',
						'rating',
						'num_ratings',
						'short_description',
						'icons',
                    	'banners'
					],
				];

				$response = wp_remote_post(
					'http://api.wordpress.org/plugins/info/1.0/',
					[
						'body' => [
							'action'  => 'query_plugins',
							'timeout' => 30,
							'request' => serialize( $args ),
						],
					]
				);

				// Bail out if API request fails.
				if ( is_wp_error( $response ) ) :
					wp_send_json_error(
						sprintf(
							/* translators: %s: Plugin website URL */
							__( 'Error loading kit. Visit: %s', 'multicurrency-for-woocommerce' ),
							'<a href="https://spiderwares.com" target="_blank">spiderwares.com</a>'
						)
					);
				endif;

				$data    = maybe_unserialize( wp_remote_retrieve_body( $response ) );
				$plugins = [];

				// Prepare plugin list.
				if ( ! empty( $data->plugins ) ) :
					foreach ( $data->plugins as $p ) :
						$plugins[] = [
							'slug'        => $p->slug,
							'name'        => $p->name,
							'version'     => $p->version,
							'downloaded'  => $p->downloaded,
							'active'      => $p->active_installs,
							'updated'     => strtotime( $p->last_updated ),
							'rating'      => $p->rating,
							'ratings'     => $p->num_ratings,
							'description' => $p->short_description,
							'icon' 		  => $p->icons['1x'],
						];
					endforeach;

					// Cache results for 1 day.
					set_transient( $transient_key, $plugins, DAY_IN_SECONDS );
				endif;
			endif;

			// Bail if no data.
			if ( empty( $plugins ) ) :
				wp_send_json_error( __( 'No plugin data found.', 'multicurrency-for-woocommerce' ) );
			endif;

			// Sort plugins by active installs.
			usort( $plugins, fn( $a, $b ) => $b['active'] <=> $a['active'] );

			// Output each plugin card.
			foreach ( $plugins as $plugin ) :
				$this->jthemes_render_plugin_card( $plugin );
			endforeach;

			wp_die();
		}

		/**
		 * Renders a single plugin card for the plugins Kit UI.
		 *
		 * @param array $plugin Plugin data.
		 * @return void
		 */
		public function jthemes_render_plugin_card( $plugin ) {
			$slug   = isset( $plugin['slug'] ) ? $plugin['slug'] : '';
			$name   = isset( $plugin['name'] ) ? $plugin['name'] : '';
			$file   = $slug . '.php';
			$image  = isset( $plugin['icon'] ) ? $plugin['icon'] : '';
			$desc   = isset( $plugin['description'] ) ? $plugin['description'] : '';
			$link   = network_admin_url( "plugin-install.php?tab=plugin-information&plugin={$slug}&TB_iframe=true&width=600&height=550" );
			$active = $this->jthemes_is_plugin_active( $slug, $file );
			$exists = $this->jthemes_is_plugin_installed( $slug, $file );

			echo '<div class="plugin-card ' . esc_attr( $slug ) . '" id="' . esc_attr( $slug ) . '">';
			echo '<div class="plugin-card-top">';
			echo '<a href="' . esc_url( $link ) . '" class="thickbox" title="' . esc_attr( $name ) . '"><img src="' . esc_url( $image ) . '" class="plugin-icon" alt="' . esc_attr( $name ) . '" /></a>';
			echo '<div class="name column-name"><h3><a href="' . esc_url( $link ) . '" class="thickbox" title="' . esc_attr( $name ) . '">' . esc_html( $name ) . '</a></h3></div>';

			echo '<div class="action-links"><ul class="plugin-action-buttons"><li>';

			if ( $exists ) :
				$url  = $active ? $this->jthemes_plugin_link( 'deactivate', $slug, $file ) : $this->jthemes_plugin_link( 'activate', $slug, $file );
				$text = $active ? __( 'Deactivate', 'multicurrency-for-woocommerce' ) : __( 'Activate', 'multicurrency-for-woocommerce' );
				echo '<a href="' . esc_url( $url ) . '" class="button">' . esc_html( $text ) . '</a>';
			else :
				$url = wp_nonce_url( self_admin_url( "update.php?action=install-plugin&plugin={$slug}" ), "install-plugin_{$slug}" );
				echo '<a href="' . esc_url( $url ) . '" class="button install-now">' . esc_html__( 'Install Now', 'multicurrency-for-woocommerce' ) . '</a>';
			endif;

			echo '</li><li><a href="' . esc_url( $link ) . '" class="thickbox open-plugin-details-modal" title="' . esc_attr( $name ) . '">' . esc_html__( 'More Details', 'multicurrency-for-woocommerce' ) . '</a></li></ul></div>';
			echo '<div class="desc column-description"><p>' . esc_html( $desc ) . '</p></div></div><div class="plugin-card-bottom">';

			if ( isset( $plugin['rating'], $plugin['ratings'] ) ) :
				echo '<div class="vers column-rating">';
				wp_star_rating(
					[
						'rating' => $plugin['rating'],
						'type'   => 'percent',
						'number' => $plugin['ratings'],
					]
				);
				echo '<span class="num-ratings">(' . esc_html( number_format_i18n( $plugin['ratings'] ) ) . ')</span></div>';
			endif;

			if ( isset( $plugin['version'] ) ) :
				echo '<div class="column-updated"><strong>' . esc_html__( 'Version:', 'multicurrency-for-woocommerce' ) . '</strong> ' . esc_html( $plugin['version'] ) . '</div>';
			endif;

			if ( isset( $plugin['active'] ) ) :
				echo '<div class="column-downloaded">' . esc_html( number_format_i18n( $plugin['active'] ) ) . esc_html__( '+ Active Installations', 'multicurrency-for-woocommerce' ) . '</div>';
			endif;

			if ( isset( $plugin['updated'] ) ) :
				echo '<div class="column-compatibility"><strong>' . esc_html__( 'Last Updated:', 'multicurrency-for-woocommerce' ) . '</strong> ' . esc_html( human_time_diff( $plugin['updated'] ) ) . ' ' . esc_html__( 'ago', 'multicurrency-for-woocommerce' ) . '</div>';
			endif;

			echo '</div></div>';
		}


		/**
		 * Check if a plugin is installed.
		 *
		 * @param string $slug Plugin slug.
		 * @param string $file Plugin main file.
		 * @return bool
		 */
		public function jthemes_is_plugin_installed( $slug, $file ) {
			return file_exists( WP_PLUGIN_DIR . "/{$slug}/{$file}" );
		}

		/**
		 * Check if a plugin is active.
		 *
		 * @param string $slug Plugin slug.
		 * @param string $file Plugin main file.
		 * @return bool
		 */
		public function jthemes_is_plugin_active( $slug, $file ) {
			return is_plugin_active( "{$slug}/{$file}" );
		}

		/**
		 * Generate plugin install/activate/deactivate action URL.
		 *
		 * @param string $action Action type (install|activate|deactivate).
		 * @param string $slug Plugin slug.
		 * @param string $file Plugin main file.
		 * @return string
		 */
		public function jthemes_plugin_link( $action, $slug, $file ) {
			$plugin = "{$slug}/{$file}";

			if ( $action === 'activate' || $action === 'deactivate' ) :
				return wp_nonce_url(
					admin_url( "plugins.php?action={$action}&plugin={$plugin}" ),
					"{$action}-plugin_{$plugin}"
				);
			endif;

			// Fallback (optional): use current page if needed
			return admin_url( "admin.php?page=jthemes" );
		}

	}

	// Instantiate the MCWC_Jthemes_Dashboard class.
	new MCWC_Jthemes_Dashboard();
}
