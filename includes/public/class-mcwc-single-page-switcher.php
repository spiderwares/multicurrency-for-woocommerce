<?php
/**
 * Public frontend functionality for MultiCurrency for WooCommerce.
 *
 * @package MultiCurrencyForWooCommerce
 */
if ( ! defined( 'ABSPATH' ) ) :
    exit;
endif;

if ( ! class_exists( 'MCWC_Single_Switcher' ) ) :

    class MCWC_Single_Switcher {

        /**
         * Class MCWC_Single_Switcher
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
            $single_product_action = ! empty( $this->settings['single_position'] ) ? explode( '-', $this->settings['single_position'] ) : [];

            if ( is_array( $single_product_action ) ) :
                add_action( $single_product_action[0], array( $this, 'single_currency_switcher' ), intval( isset( $single_product_action[1] ) ? $single_product_action[1] : 10 ) );
            endif;
        }

		
		/**
		 * Outputs the frontend currency switcher HTML in the footer.
		 */
        public function single_currency_switcher() {

            $currencies = $this->get_available_currencies();

            if ( empty( $currencies ) ) :
                return;
            endif;

            $map    = require MCWC_PATH . 'includes/static/flag.php';
            $style  = isset( $this->settings['single_switcher_style'] ) ? $this->settings['single_switcher_style'] : 'no';
            $layout = isset( $this->settings['switcher_layout'] ) ? $this->settings['switcher_layout'] : 'split';

            switch ( $style ) :
                case 'flag':
                    $template = 'single-switcher/mcwc-style-flag.php';
                    break;
                case 'flag_code':
                    $template = 'single-switcher/mcwc-style-flag-code.php';
                    break;
                case 'flag_symbol':
                    $template = 'single-switcher/mcwc-style-flag-symbol.php';
                    break;
                case 'flag_price':
                    $template = 'single-switcher/mcwc-style-flag-price.php';
                    break;
                case 'no':
                default:
                    $template = 'single-switcher/index.php';
                    break;
            endswitch;

            wc_get_template(
                $template, 
                array(
                    'currencies'        => $currencies,
                    'map'               => $map,
                    'layout'            => $layout,
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

            global $product;

            if ( ! is_a( $product, 'WC_Product' ) ) :
                global $post;
                $product = wc_get_product( $post->ID );
            endif;

            foreach ( $all as $currency ) :
                if ( isset( $currency['hidden'] ) && $currency['hidden'] === 'yes' ) :
                    continue;
                endif;

                $code       = isset( $currency['currency'] ) ? $currency['currency'] : '';
                $symbol     = ! empty( $currency['symbol'] ) ? $currency['symbol'] : get_woocommerce_currency_symbol( $code );
                $rate       = isset( $currency['rate'] ) ? floatval( $currency['rate'] ) : 1;
                $fee        = isset( $currency['fee'] ) ? floatval( $currency['fee'] ) : 0;
                $decimals   = isset( $currency['decimals'] ) ? intval( $currency['decimals'] ) : 2;
                $base_price = is_object( $product ) ? floatval( get_post_meta( $product->get_id(), '_price', true ) ) : 0;
                
                // Calculate price with conversion rate and fee
                $converted = $base_price * $rate;
                if ( $fee > 0 ) :
                    $converted = $converted + $fee;
                endif;

                // Format the price using WooCommerce formatting
                $price_html = wc_price(
                    round( $converted, $decimals ),
                    array(
                        'currency' => $code,
                    )
                );

                $filtered[] = [
                    'code'   => $code,
                    'symbol' => $symbol,
                    'price'  => $price_html,
                ];
            endforeach;

            return $filtered;
        }

    }

    new MCWC_Single_Switcher();

endif;