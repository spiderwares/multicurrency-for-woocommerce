<?php
/**
 * Plugin Name:       Multi Currency for WooCommerce
 * Description:       Add multi-currency support to your WooCommerce store. Automatically convert prices and allow customers to switch currencies with ease.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            jthemesstudio
 * Author URI:        https://jthemes.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Requires Plugins:  woocommerce
 * Text Domain:       multicurrency-for-woocommerce
 *
 * @package MultiCurrency_For_WooCommerce
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'MCWC_FILE' ) ) :
    define( 'MCWC_FILE', __FILE__ ); // Define the plugin file path.
endif;

if ( ! defined( 'MCWC_BASENAME' ) ) :
    define( 'MCWC_BASENAME', plugin_basename( MCWC_FILE ) ); // Define the plugin basename.
endif;

if ( ! defined( 'MCWC_VERSION' ) ) :
    define( 'MCWC_VERSION', time() ); // Define the plugin version.
endif;

if ( ! defined( 'MCWC_PATH' ) ) :
    define( 'MCWC_PATH', plugin_dir_path( __FILE__ ) ); // Define the plugin directory path.
endif;

if ( ! defined( 'MCWC_TEMPLATE_PATH' ) ) :
	define( 'MCWC_TEMPLATE_PATH', MCWC_PATH . '/templates/' ); // Define the plugin directory path.
endif;

if ( ! defined( 'MCWC_URL' ) ) :
    define( 'MCWC_URL', plugin_dir_url( __FILE__ ) ); // Define the plugin directory URL.
endif;

if ( ! defined( 'MCWC_REVIEWS' ) ) :
    define( 'MCWC_REVIEWS', 'https://jthemes.com/' ); // Define the plugin directory URL.
endif;

if ( ! defined( 'MCWC_CHANGELOG' ) ) :
    define( 'MCWC_CHANGELOG', 'https://jthemes.com/' ); // Define the plugin directory URL.
endif;

if ( ! defined( 'MCWC_DISCUSSION' ) ) :
    define( 'MCWC_DISCUSSION', 'https://jthemes.com/' ); // Define the plugin directory URL.
endif;

if ( ! defined( 'MCWC_PRO_VERSION_URL' ) ) :
    define( 'MCWC_PRO_VERSION_URL', '#' ); // Define the upgrade URL.
endif;

if ( ! class_exists( 'MCWC', false ) ) :
    include_once MCWC_PATH . 'includes/class-mcwc.php';
endif;

$GLOBALS['MCWC'] = MCWC::instance();


// Set default data when active
register_activation_hook( __FILE__, array( 'MCWC', 'activate' ) );