<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'MCWC' ) ) :

    /**
     * Main MCWC Class
     *
     * @class MCWC
     * @version 1.0.0
     */
    final class MCWC {

        /**
         * The single instance of the class.
         *
         * @var MCWC
         */
        protected static $instance = null;

        /**
         * Constructor for the class.
         */
        public function __construct() {
            $this->event_handler();
        }
        
        /**
         * Initialize hooks and filters.
         */
        private function event_handler() {
            // Register plugin activation hook
            register_activation_hook( MCWC_FILE, array( 'MCWC_install', 'install' ) );

            // Hook to install the plugin after plugins are loaded
            add_action( 'plugins_loaded', array( $this, 'mcwc_install' ), 11 );
            add_action( 'mcwc_init', array( $this, 'includes' ), 11 );
        }

        /**
         * Main MCWC Instance.
         *
         * Ensures only one instance of MCWC is loaded or can be loaded.
         *
         * @static
         * @return MCWC - Main instance.
         */
        public static function instance() {
            if ( is_null( self::$instance ) ) :
                self::$instance         = new self();

                /**
                 * Fire a custom action to allow dependencies
                 * after the successful plugin setup
                 */
                do_action( 'mcwc_plugin_loaded' );
            endif;
            return self::$instance;
        }

        /**
         * Function to display admin notice if WooCommerce is not active.
         */
        public function woocommerce_admin_notice() {
            ?>
            <div class="error">
                <p><?php esc_html_e( 'Multi Currency for WooCommerce is enabled but not effective. It requires WooCommerce to work.', 'multicurrency-for-woocommerce' ); ?></p>
            </div>
            <?php
        }

        /**
         * Function to initialize the plugin after WooCommerce is loaded.
         */
        public function mcwc_install() {
            if ( ! function_exists( 'WC' )  ) : // Check if WooCommerce is active.
                add_action( 'admin_notices', array( $this, 'woocommerce_admin_notice' ) ); // Display admin notice if WooCommerce is not active.
            else :
                do_action( 'mcwc_init' ); // Initialize the plugin.
            endif;
        }

        /**
         * Include required files.
         *
         * @access private
         */
        public function includes() {
            if( is_admin() ) :
                $this->includes_admin();
           else :
                $this->includes_public();
            endif;

            require_once MCWC_PATH . 'includes/mcwc-funcation.php'; 
            require_once MCWC_PATH . 'includes/admin/class-mcwc-exchange-rate.php'; 
            require_once MCWC_PATH . 'includes/public/class-mcwc-switcher.php';
            require_once MCWC_PATH . 'includes/public/class-mcwc-product-price.php';
            require_once MCWC_PATH . 'includes/public/class-mcwc-geo-detector.php';
        }
        
        /**
         * Include Admin required files.
         *
         * @access private
         */
        public function includes_admin() {
            require_once MCWC_PATH . 'includes/class-mcwc-install.php'; 
            require_once MCWC_PATH . 'includes/admin/dashboard/class-jthemes-dashboard.php';
            require_once MCWC_PATH . 'includes/admin/settings/class-mcwc-admin-menu.php';
            require_once MCWC_PATH . 'includes/admin/class-mcwc-simple-product-metabox.php';
            require_once MCWC_PATH . 'includes/admin/class-mcwc-variable-product-metabox.php';
        }

        /**
         * Include Public required files.
         *
         * @access private
         */
        public function includes_public(){
            require_once MCWC_PATH . 'includes/public/class-mcwc-switcher-sidebar.php';
            require_once MCWC_PATH . 'includes/public/class-mcwc-single-page-switcher.php';
        }

        /**
         * Execute function on plugin activation
         */
        public static function activate() {

            $defaultOptions = require_once MCWC_PATH . 'includes/static/default-option.php';
            // Get the existing option value
            $existingOption = get_option( 'mcwc_settings' );
            // If the option is not set, update it with the default value
            if ( ! $existingOption ) :
                update_option( 'mcwc_settings', $defaultOptions );
            endif;
        
        }

    }

endif;
