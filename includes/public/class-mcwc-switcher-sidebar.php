<?php
/**
 * Public frontend functionality for MultiCurrency for WooCommerce.
 *
 * @package MultiCurrencyForWooCommerce
 */
if ( ! defined( 'ABSPATH' ) ) :
    exit;
endif;

if ( ! class_exists( 'MCWC_Switcher_Sidebar' ) ) :

    class MCWC_Switcher_Sidebar {

        /**
         * Class MCWC_Switcher_Sidebar
         *
         * Handles the frontend currency switcher display and functionality.
         */
        private $settings;

		/**
		 * Constructor.
		 *
		 * Initializes the class by setting up hooks and retrieving settings.
		 */
        public function __construct() {
            $this->settings = get_option( 'mcwc_settings', array() );
            $this->event_handler();
        }
        
		/**
		 * Registers WordPress actions for enqueuing scripts and rendering the switcher.
		 */
        private function event_handler(){
            add_filter( 'mcwc_flag_option', array( $this, 'override_flags' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'wp_footer', array( $this, 'currency_switcher' ) );
            add_action( 'init', array( $this, 'start_session' ), 1 );
        }

        public function override_flags( $flags ) {
            if ( ! isset( $this->settings['custom_flag'] ) || empty( $this->settings['custom_flag'] ) ) :
                return $flags;
            endif;

            // Split by any whitespace (space, tab, newline)
            $entries = preg_split( '/\s+/', $this->settings['custom_flag'] );

            foreach ( $entries as $entry ) :
                $entry = trim( $entry );
                if ( empty( $entry ) || strpos( $entry, ',' ) === false ) :
                    continue;
                endif;

                list( $currency_code, $country_code ) = array_map( 'trim', explode( ',', $entry ) );

                if ( ! empty( $currency_code ) && ! empty( $country_code ) ) :
                    $flags[ strtoupper( $currency_code ) ] = strtolower( $country_code );
                endif;
            endforeach;

            return $flags;
        }

		/**
		 * Enqueue frontend styles and scripts.
		 */
        public function enqueue_scripts() {

            wp_enqueue_style(
                'mcwc-frontend',
                MCWC_URL . '/assets/css/mcwc-frontend.css',
                [],
                MCWC_VERSION 
            );

            wp_enqueue_style(
                'mcwc-flag',
                MCWC_URL . '/assets/css/mcwc-flag.css',
                [],
                MCWC_VERSION 
            );
            
            $dynamic_css = $this->dynamic_css();

            if ( ! empty( $dynamic_css ) ) :
                wp_add_inline_style( 'mcwc-frontend', $dynamic_css );
            endif;

        }

        public function dynamic_css(){
            ob_start();

            wc_get_template(
                'dynamic-style.php', 
                array(
                    'settings'  => $this->settings
                ),
                'multicurrency-for-woocommerce/',
                MCWC_TEMPLATE_PATH
            );

            return ob_get_clean();
        }

		/**
		 * Outputs the frontend currency switcher HTML in the footer.
		 */
        public function currency_switcher() {

            $currencies = $this->get_available_currencies();

            if ( empty( $currencies ) || ! $this->should_show_switcher() ) :
                return;
            endif;

            $map            = require MCWC_PATH . 'includes/static/flag.php';
            $title          = isset( $this->settings['switcher_title'] ) ? $this->settings['switcher_title'] : '';
            $position       = isset( $this->settings['design_position'] ) ? $this->settings['design_position'] : 'left';
            $style          = isset( $this->settings['sidebar_style'] ) ? $this->settings['sidebar_style'] : 'default';
            $rel_nofollow   = ( isset( $this->settings['rel_nofollow'] ) && $this->settings['rel_nofollow'] === 'yes' ) ? 'rel=nofollow' : '';

            switch ( $style ) :
                case 'symbol':
                    $template = 'sidebar/mcwc-style-symbol.php';
                    break;
                case 'flag':
                    $template = 'sidebar/mcwc-style-flag.php';
                    break;
                case 'flag_code':
                    $template = 'sidebar/mcwc-style-flag-code.php';
                    break;
                case 'flag_symbol':
                    $template = 'sidebar/mcwc-style-flag-symbol.php';
                    break;
                case 'default':
                default:
                    $template = 'sidebar/mcwc-style-default.php';
                    break;
            endswitch;

            wc_get_template(
                $template, 
                array(
                    'currencies'        => $currencies,
                    'title'             => $title,
                    'position'          => $position,
                    'map'               => $map,
                    'rel_nofollow'      => $rel_nofollow,
                    'selected_currency' => mcwc_get_selected_currency(),
                ),
                'multicurrency-for-woocommerce/',
                MCWC_TEMPLATE_PATH
            );
        }

        /**
		 * Get the list of available (non-hidden) currencies from settings.
		 *
		 * @return array Array of currencies with 'code' and 'symbol' keys.
		 */
        private function get_available_currencies() {
            $all      = isset( $this->settings['currencies'] ) ? $this->settings['currencies'] : [];
            $filtered = [];

            foreach ( $all as $currency ) :
                if ( isset( $currency['hidden'] ) && $currency['hidden'] === 'yes' ) :
                    continue;
                endif;

                $code   = isset( $currency['currency'] ) ? $currency['currency'] : '';
                $symbol = ! empty( $currency['symbol'] ) ? $currency['symbol'] : get_woocommerce_currency_symbol( $code );

                $filtered[] = [
                    'code'   => $code,
                    'symbol' => $symbol,
                ];
            endforeach;

            return $filtered;
        }


        /*------------------------ switcher funcation -----------------------------*/

        /**
         * Check if the currency switcher is enabled.
         *
         * @return bool
         */
        private function is_switcher_enabled() {
            return ! empty( $this->settings['enable'] ) && $this->settings['enable'] === 'yes';
        }

        private function should_show_switcher() {

            if ( empty( $this->settings['enable'] ) || $this->settings['enable'] !== 'yes' ) :
                return false;
            endif;

            if ( is_front_page() && $this->settings['hide_on_home'] === 'yes' ) :
                return false;
            endif;

            if ( is_shop() && $this->settings['hide_on_shop'] === 'yes' ) :
                return false;
            endif;

            if ( is_product() && $this->settings['hide_on_single_product'] === 'yes' ) :
                return false;
            endif;

            if ( is_product_category() && $this->settings['hide_on_product_category'] === 'yes' ) :
                return false;
            endif;

            if ( is_cart() && $this->settings['hide_on_cart'] === 'yes' ) :
                return false;
            endif;

            if ( is_checkout() && $this->settings['hide_on_checkout'] === 'yes' ) :
                return false;
            endif;

            if ( ! empty( $this->settings['conditional_tags'] ) ) :
                $expr = trim( $this->settings['conditional_tags'] );

                // Disallow dangerous code
                if ( preg_match( '/[^a-zA-Z0-9_\s\(\)\[\],!\'"]/', $expr ) ) :
                    return false;
                endif;

                try {
                    // Eval safe expression
                    $result = eval( 'return (' . $expr . ') ? true : false;' );
                    if ( ! $result ) :
                        return false;
                    endif;
                } catch ( \Throwable $e ) {
                    return false;
                }
            endif;

            return true;
        }

        public function start_session() {
            if ( ! session_id() && ! headers_sent() ) :
                session_start();
            endif;
        }


    }

    new MCWC_Switcher_Sidebar();

endif;