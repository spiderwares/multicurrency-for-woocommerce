<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class MCWC_Cache_Mode_Handler {

    protected $settings;
    protected $use_cache_mode;

    public function __construct() {
        $this->settings = get_option( 'mcwc_settings', [] );
        $this->use_cache_mode = isset( $this->settings['use_cache'] ) ? $this->settings['use_cache'] : 'ajax_override';

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_footer', array( $this, 'output_json_prices' ) );

        if ( $this->use_cache_mode === 'ajax_override' ) :
            add_action( 'wp_ajax_mcwc_get_updated_prices', array( $this, 'handle_ajax_price_update' ) );
            add_action( 'wp_ajax_nopriv_mcwc_get_updated_prices', array( $this, 'handle_ajax_price_update' ) );
        endif;
    }

    public function enqueue_scripts() {

        wp_enqueue_script(
            'mcwc-cache-handler',
            MCWC_URL . '/assets/js/mcwc-cache-handler.js',
            array(),
            MCWC_VERSION,
            true 
        );

        // wp_localize_script( 'mcwc-cache-handler', 'MCWC_Settings', [
        //     'use_cache' => $this->use_cache_mode,
        //     'ajax_url'  => admin_url( 'admin-ajax.php' ),
        //     'currency'  => $this->get_current_currency(),
        // ] );
    }

    public function output_json_prices() {
        if ( $this->use_cache_mode !== 'json_override' ) :
            return;
        endif;

        $prices = [];

        if ( is_shop() || is_product_category() || is_product_tag() ) {
            $products = wc_get_products([
                'limit'         => -1,
                'status'        => 'publish',
                'visibility'    => 'visible',
            ]);

            foreach ( $products as $product ) :
                $price = $this->get_converted_price( $product );
                $prices[ $product->get_id() ] = [
                    'formatted' => wc_price( $price ),
                    'raw'       => $price,
                ];
            endforeach;

            echo '<script type="application/json" class="mcwc-price-json">' . wp_json_encode( $prices ) . '</script>';
        }
    }

    public function handle_ajax_price_update() {
        $currency = sanitize_text_field( isset( $_POST['currency'] ) ? $_POST['currency'] : '' );

        if ( empty( $currency ) ) :
            wp_send_json_error( [ 'message' => 'Currency not provided' ] );
        endif;

        $products = wc_get_products([
            'limit' => -1,
            'status' => 'publish',
            'visibility' => 'visible',
        ]);

        $data = [];

        foreach ( $products as $product ) :
            $price = $this->get_converted_price( $product, $currency );
            $data[ $product->get_id() ] = [
                'formatted' => wc_price( $price ),
                'raw'       => $price,
            ];
        endforeach;

        wp_send_json_success( $data );
    }

    protected function get_converted_price( $product, $currency = '' ) {
        $base_price         = (float) $product->get_price();
        $selected_currency  = $currency ?: $this->get_current_currency();
        $rates              = isset( $this->settings['conversion_rates'] ) ? $this->settings['conversion_rates'] : [];
        $rate               = isset( $rates[ $selected_currency ] ) ? (float) $rates[ $selected_currency ] : 1;

        return $base_price * $rate;
    }

    protected function get_current_currency() {
        return isset( $_COOKIE['mcwc_currency'] ) ? sanitize_text_field( $_COOKIE['mcwc_currency'] ) : $this->settings['default_currency'];
    }
}
