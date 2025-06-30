<?php
if ( ! defined( 'ABSPATH' ) ) :
    exit;
endif;

/**
 * Class MCWC_Frontend_Mini_Cart
 * 
 * Handles WooCommerce Mini Cart rendering to ensure correct currency totals are shown.
 * Recalculates totals and enqueues refresh logic if currency is switched via query param.
 *
 * @package MultiCurrencyForWooCommerce
 */
class MCWC_Frontend_Mini_Cart {

    protected $settings;

    public function __construct() {
        $this->settings = get_option( 'mcwc_settings', array() );

        if ( isset( $this->settings['enable'] ) && $this->settings['enable'] === 'yes' ) {
            add_action( 'woocommerce_before_mini_cart', array( $this, 'recalculate_cart_totals' ), 9999 );
            add_action( 'wp_enqueue_scripts', array( $this, 'conditionally_enqueue_cart_script' ) );
        }
    }

    /**
     * Force WooCommerce to recalculate totals when the mini cart is loaded.
     */
    public function recalculate_cart_totals() {
        if ( WC()->cart ) {
            WC()->cart->calculate_totals();
        }
    }

    /**
     * Enqueues a script to handle mini cart refresh if currency is switched via URL.
     */
    public function conditionally_enqueue_cart_script() {

        // Check for currency in URL and match with available currencies
        
            $currency_code = mcwc_get_selected_currency();
            $currency_codes = array_column( $this->settings['currencies'] ?? array(), 'currency' );

            if ( in_array( $currency_code, $currency_codes, true ) ) :
                wp_enqueue_script(
                    'mcwc-mini-cart',
                    MCWC_URL . 'assets/js/mcwc-mini-cart.js',
                    array( 'jquery' ),
                    MCWC_VERSION,
                    true
                );
            endif;

    }
}

new MCWC_Frontend_Mini_Cart();