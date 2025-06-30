<?php
/**
 * Public frontend functionality for MultiCurrency for WooCommerce.
 *
 * @package MultiCurrencyForWooCommerce
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'MCWC_currency_Switcher' ) ) :

    class MCWC_currency_Switcher {

        /**
         * Class MCWC_currency_Switcher
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
		 * Registers WordPress actions.
		 *
		 * Hook into 'init' to handle currency switching.
		 */
        private function event_handler(){
            add_action( 'init', array( $this, 'handle_currency_switcher' ) );
        } 

        /**
		 * Handles currency switching from GET requests.
		 *
		 * Checks nonce, sanitizes input, and sets currency in session or cookie.
		 * Should be used as a fallback when JavaScript is disabled.
		 *
		 * @return void
		 */
        public function handle_currency_switcher() {
            if ( isset( $_GET['mcwc_currency'] ) && isset( $_GET['mcwc_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['mcwc_nonce'] ) ), 'mcwc_switch_currency' ) ) :
                $currency = sanitize_text_field( wp_unslash( $_GET['mcwc_currency'] ) );

                if ( ! $currency ) :
                    return;
                endif;

                if ( isset( $this->settings['use_session'] ) && $this->settings['use_session'] === 'yes' ) :
                    if ( ! session_id() ) :
                        session_start();
                    endif;
                    $_SESSION['mcwc_selected_currency'] = $currency;
                else :
                    setcookie( 'mcwc_selected_currency', $currency, time() + (30 * DAY_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN );
                endif;
            endif;
        }
        
    }
    
	// Initialize the currency switcher.
    new MCWC_currency_Switcher();

endif;