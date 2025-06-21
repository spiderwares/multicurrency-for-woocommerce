<?php
/**
 * Admin Menu Class for Multi Currency for WooCommerce.
 *
 * @package MultiCurrencyForWooCommerce
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'MCWC_Admin_Menu' ) ) :

    /**
     * Main MCWC_Admin_Menu Class
     *
     * @class MCWC_Admin_Menu
     * @version 1.0.0
     */
    final class MCWC_Admin_Menu {

        /**
         * The single instance of the class.
         *
         * @var MCWC_Admin_Menu
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
            add_action( 'admin_init', array( $this, 'register_settings' ) );
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
            add_filter( 'pre_update_option_mcwc_settings', array( $this, 'filter_data_before_update' ), 10, 3 );
        }

        /**
		 * Register plugin settings.
		 */
        public function register_settings() {
            register_setting(
                'mcwc_settings',
                'mcwc_settings',
                [
                    'sanitize_callback' => [ $this, 'sanitize_settings' ],
                ]
            );
        }

        /**
         * Sanitize settings and add success message.
         */
        public function sanitize_settings( $input ) {
            add_settings_error(
                'mcwc_settings',
                'mcwc_settings_updated',
                esc_html__( 'Settings saved successfully.', 'multicurrency-for-woocommerce' ),
                'updated'
            );
            return $input;
        }

		/**
		 * Add submenu page under the custom 'jthemes' menu.
		 */
        public function admin_menu() {
            add_submenu_page( 
                'jthemes', 
                esc_html__( 'Jthemes Multi Currency', 'multicurrency-for-woocommerce' ), 
                esc_html__( 'Multi Currency', 'multicurrency-for-woocommerce' ), 
                'manage_options', 
                'jthemes-mcwc', 
                [ $this,'admin_menu_content' ] 
            );
        }

        /**
		 * Load the admin menu page content.
		 */
        public function admin_menu_content() {
            require_once MCWC_PATH . 'includes/admin/settings/views/admin-settings.php';

            $active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';
            require_once MCWC_PATH . 'includes/admin/settings/views/multicurrency-menu.php';
        }

        /**
         * Filter data before updating options in the database.
         *
         * @param mixed  $value     The new value to be updated.
         * @param mixed  $old_value The previous value.
         * @param string $option    The option name.
         *
         * @return mixed The filtered data.
         */
        public function filter_data_before_update( $value, $old_value, $option ) {
            $value     = (array) $value;
            $old_value = (array) $old_value;

            if ( isset( $value['currencies'] ) && is_array( $value['currencies'] ) ) :

                $currencies             = $value['currencies'];
                $formatted_currencies   = array();

                if ( isset($currencies['currency']) && is_array($currencies['currency']) ) :
                    $count = count($currencies['currency']);
                    for ( $i = 0; $i < $count; $i++ ) :
                        if ( $i == 0 ) continue;
                        $formatted_currencies[] = array(
                            'default'   => isset( $currencies['default'][0] )  ? sanitize_text_field( $currencies['default'][0] )       : '',
                            'hidden'    => isset( $currencies['hidden'][$i] )   ? sanitize_text_field( $currencies['hidden'][$i] )      : 'no',
                            'currency'  => isset( $currencies['currency'][$i] ) ? sanitize_text_field( $currencies['currency'][$i] )    : '',
                            'position'  => isset( $currencies['position'][$i] ) ? sanitize_text_field( $currencies['position'][$i] )    : '',
                            'rate'      => isset( $currencies['rate'][$i] )     ? sanitize_text_field( $currencies['rate'][$i] )        : '',
                            'fee'       => isset( $currencies['fee'][$i] )      ? sanitize_text_field( $currencies['fee'][$i] )         : '',
                            'decimals'  => isset( $currencies['decimals'][$i] ) ? intval( $currencies['decimals'][$i] )                 : 2,
                            'symbol'    => isset( $currencies['symbol'][$i] )   ? sanitize_text_field( $currencies['symbol'][$i] )      : '',
                        );
                    endfor;
                endif;
                $value['currencies'] = $formatted_currencies;
            endif;

            if ( isset( $value['conditional_tags'] ) ) :
                $value['conditional_tags'] = $this->sanitize_conditional_tags( $value['conditional_tags'] );
            endif;

            $data = array_merge( $old_value, $value );
            return $data;
        }

        public function sanitize_conditional_tags( $value ) {
            return trim( sanitize_text_field( $value ) );
        }

    }
    new MCWC_Admin_Menu();
endif;