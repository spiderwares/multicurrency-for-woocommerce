<?php
/**
 * Public frontend functionality for MultiCurrency for WooCommerce.
 *
 * @package MultiCurrencyForWooCommerce
 */
if ( ! defined( 'ABSPATH' ) ) :
    exit;
endif;

if ( ! class_exists( 'MCWC_Product_Price' ) ) :

    class MCWC_Product_Price {

        /**
         * Class MCWC_Product_Price
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
            add_filter( 'woocommerce_product_get_price', array( $this, 'convert_price_by_currency' ), 10, 2 );
            add_filter( 'woocommerce_product_get_regular_price', array( $this, 'convert_price_by_currency' ), 10, 2 );
            add_filter( 'woocommerce_product_get_sale_price', array( $this, 'convert_price_by_currency' ), 10, 2 );   
            add_filter( 'woocommerce_currency', array( $this, 'override_woocommerce_currency' ) );
        }

        public function convert_price_by_currency( $price, $product ) {
            $selected_currency = $this->mcwc_get_selected_currency();
            $all_currencies    = isset( $this->settings['currencies'] ) ? $this->settings['currencies'] : [];

            if ( ! $selected_currency || empty( $all_currencies ) ) :
                return $price;
            endif;

            // Find selected currency data
            $currency_data = null;
            foreach ( $all_currencies as $currency ) :
                if ( isset( $currency['currency'] ) && $currency['currency'] === $selected_currency ) :
                    $currency_data = $currency;
                    break;
                endif;
            endforeach;

            if ( ! $currency_data ) :
                return $price; // Fallback if currency not matched
            endif;

            $rate     = isset( $currency_data['rate'] ) ? floatval( $currency_data['rate'] ) : 1;
            $fee      = isset( $currency_data['fee'] ) ? floatval( $currency_data['fee'] ) : 0;
            $decimals = isset( $currency_data['decimals'] ) ? intval( $currency_data['decimals'] ) : 2;
            $position = isset( $currency_data['position'] ) ? $currency_data['position'] : 'left';
            $symbol   = isset( $currency_data['symbol'] ) && ! empty( $currency_data['symbol'] )
                        ? $currency_data['symbol']
                        : get_woocommerce_currency_symbol( $selected_currency );

            // Calculate converted price
            $converted_price = floatval( $price ) * $rate;
            if ( $fee > 0 ) :
                $converted_price += $fee;
            endif;

                    
            return $formatted_price = number_format( $converted_price, $decimals );

            // Format with symbol based on position
            switch ( $position ) {
                case 'left':
                    $price = $symbol . $formatted_price;
                case 'left_space':
                    $price = $symbol . ' ' . $formatted_price;
                case 'right':
                    $price = $formatted_price . $symbol;
                case 'right_space':
                    $price = $formatted_price . ' ' . $symbol;
                default:
                    $price = $symbol . $formatted_price;
            }

            return $price;
        }

        public function mcwc_get_selected_currency() {
            $settings = get_option( 'mcwc_settings', [] );

            if ( isset( $settings['use_session'] ) && $settings['use_session'] === 'yes' ) :
                if ( ! session_id() && ! headers_sent() ) :
                    session_start();
                endif;
                return isset( $_SESSION['mcwc_selected_currency'] ) ? $_SESSION['mcwc_selected_currency'] : get_woocommerce_currency();
            endif;

            return isset( $_COOKIE['mcwc_selected_currency'] ) ? sanitize_text_field( $_COOKIE['mcwc_selected_currency'] ) : get_woocommerce_currency();
        }

        public function override_woocommerce_currency( $currency ) {
            $selected_currency = $this->mcwc_get_selected_currency();
            return $selected_currency ? $selected_currency : $currency;
        }
        
    }

    new MCWC_Product_Price();

endif;