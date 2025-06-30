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

        private $settings;

        public function __construct() {
            $this->settings = get_option( 'mcwc_settings', array() );
            $this->event_handler();
        }

        private function event_handler() {
            add_filter( 'woocommerce_currency', array( $this, 'override_woocommerce_currency' ), 999 );

            add_filter( 'woocommerce_product_get_price', array( $this, 'convert_price_by_currency' ), 99, 2 );
            add_filter( 'woocommerce_product_get_regular_price', array( $this, 'convert_price_by_currency' ), 99, 2 );
            add_filter( 'woocommerce_product_get_sale_price', array( $this, 'convert_price_by_currency' ), 99, 2 );

            add_filter( 'woocommerce_product_variation_get_price', array( $this, 'convert_price_by_currency' ), 99, 2 );
            add_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'convert_price_by_currency' ), 99, 2 );
            add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'convert_price_by_currency' ), 99, 2 );

            add_filter( 'woocommerce_variation_prices_price', array( $this, 'convert_price_by_currency' ), 99, 2 );

        }

        public function convert_price_by_currency( $price, $product ) {
            if ( empty( $price ) || floatval( $price ) <= 0 ) :
                return $price;
            endif;

            $converted = $this->get_converted_price( $price, $product );
            return $converted ? $converted['price'] : $price;
        }


        private function get_converted_price( $price, $product ) {
            $selected_currency = mcwc_get_selected_currency();
            $currencies        = isset( $this->settings['currencies'] ) ? $this->settings['currencies'] : [];

            if ( ! $selected_currency || empty( $currencies ) ) :
                return false;
            endif;

            // Check fixed prices if enabled
            if ( isset( $this->settings['fixed_price'] ) && $this->settings['fixed_price'] === 'yes' ) :
                $product_id = $product->get_id();

                $regular_price_meta = json_decode( get_post_meta( $product_id, '_regular_price_mcwc', true ), true );
                $sale_price_meta    = json_decode( get_post_meta( $product_id, '_sale_price_mcwc', true ), true );

                $sale_price_available    = isset( $sale_price_meta[ $selected_currency ] ) && is_numeric( $sale_price_meta[ $selected_currency ] );
                $custom_price            = null;
                $regular_price_available = isset( $regular_price_meta[ $selected_currency ] ) && is_numeric( $regular_price_meta[ $selected_currency ] );

                // Prioritize sale price if available
                if ( $sale_price_available ) :
                    $custom_price = floatval( $sale_price_meta[ $selected_currency ] );
                elseif ( $regular_price_available ) :
                    $custom_price = floatval( $regular_price_meta[ $selected_currency ] );
                endif;

                if ( $custom_price !== null ) :
                    $currency_data = $this->get_currency_data( $selected_currency, $currencies );
                    return [
                        'price'    => round( $custom_price, $currency_data['decimals'] ),
                        'decimals' => $currency_data['decimals'],
                        'symbol'   => $currency_data['symbol'],
                        'position' => $currency_data['position'],
                        'currency' => $selected_currency,
                    ];
                endif;
            endif;

            // Fallback to rate-based conversion
            foreach ( $currencies as $currency ) :
                if ( $currency['currency'] === $selected_currency ) :
                    $rate     = isset( $currency['rate'] ) ? floatval( $currency['rate'] ) : 1;
                    $fee      = isset( $currency['fee'] ) ? floatval( $currency['fee'] ) : 0;
                    $decimals = isset( $currency['decimals'] ) ? intval( $currency['decimals'] ) : 2;

                    $converted_price = floatval( $price ) * $rate;
                    if ( $fee > 0 ) :
                        $converted_price += $fee;
                    endif;

                    return [
                        'price'    => round( $converted_price, $decimals ),
                        'decimals' => $decimals,
                        'symbol'   => isset( $currency['symbol'] ) && $currency['symbol'] ? $currency['symbol'] : get_woocommerce_currency_symbol( $selected_currency ),
                        'position' => isset( $currency['position'] ) ? $currency['position'] : 'left',
                        'currency' => $selected_currency,
                    ];
                endif;
            endforeach;

            return false;
        }

        private function get_currency_data( $currency_code, $currencies ) {
            foreach ( $currencies as $currency ) :
                if ( $currency['currency'] === $currency_code ) :
                    return [
                        'decimals' => isset( $currency['decimals'] ) ? intval( $currency['decimals'] ) : 2,
                        'symbol'   => isset( $currency['symbol'] ) && $currency['symbol'] ? $currency['symbol'] : get_woocommerce_currency_symbol( $currency_code ),
                        'position' => isset( $currency['position'] ) ? $currency['position'] : 'left',
                    ];
                endif;
            endforeach;
            // Default fallback
            return [
                'decimals' => 2,
                'symbol'   => get_woocommerce_currency_symbol( $currency_code ),
                'position' => 'left',
            ];
        }

        public function convert_price_html_for_variable_product( $price_html, $product ) {
            if ( ! $product->is_type( 'variable' ) ) :
                return $price_html;
            endif;

            $min_price      = $product->get_variation_price( 'min', true );
            $max_price      = $product->get_variation_price( 'max', true );
            $min_converted  = $this->get_converted_price( $min_price, $product );
            $max_converted  = $this->get_converted_price( $max_price, $product );

            if ( ! $min_converted || ! $max_converted ) :
                return $price_html;
            endif;

            $formatted_min = wc_price( $min_converted['price'], [ 'currency' => $min_converted['currency'] ] );
            $formatted_max = wc_price( $max_converted['price'], [ 'currency' => $max_converted['currency'] ] );

            return $formatted_min . ' - ' . $formatted_max;
        }

        public function override_woocommerce_currency( $currency ) {
            if ( is_admin() || wp_doing_ajax() && is_user_logged_in() && current_user_can('edit_posts') ) :
                return $currency; // Return default in backend or admin ajax
            endif;
            $selected = mcwc_get_selected_currency();
            return $selected ? $selected : $currency;
        }

    }

    new MCWC_Product_Price();

endif;