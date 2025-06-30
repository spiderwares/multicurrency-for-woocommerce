<?php
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

class MCWC_Frontend_Cache {
	protected $settings;
	protected $price_args = [];
	protected $mini_cart = false;

	public function __construct() {
		$this->settings = get_option( 'mcwc_settings', true );
		if ( empty( $this->settings['enable'] ) || $this->settings['enable'] !== 'yes' ) :
			return;
        endif;

		add_action( 'wp_ajax_mcwc_get_prices', [ $this, 'get_converted_prices' ] );
		add_action( 'wp_ajax_nopriv_mcwc_get_prices', [ $this, 'get_converted_prices' ] );

		$cache_mode = isset( $this->settings['use_cache'] ) ? $this->settings['use_cache'] : 'none';

		switch ( $cache_mode ) :
			case 'ajax_override':
				add_filter( 'woocommerce_get_price_html', [ $this, 'wrap_price_for_ajax' ], PHP_INT_MAX, 2 );
				break;

			case 'json_override':
				add_filter( 'wc_price', [ $this, 'inject_price_cache_json' ], 1000, 5 );
				add_action( 'woocommerce_before_mini_cart_contents', [ $this, 'start_mini_cart' ] );
				add_action( 'woocommerce_after_mini_cart', [ $this, 'end_mini_cart' ] );
				break;
        endswitch;
	}

	public function wrap_price_for_ajax( $price, $product ) {
		if ( wp_doing_ajax() ) :
			return $price;
		endif;

		$tag = ( strpos( $price, '<div' ) !== false || strpos( $price, '<p' ) !== false ) ? 'div' : 'span';
		return sprintf( '<%1$s class="mcwc-ajax-price" data-product-id="%2$d">%3$s</%1$s>', $tag, $product->get_id(), $price );
	}

	public function inject_price_cache_json( $return, $price, $args, $unformatted_price, $original_price ) {
		if ( is_cart() || is_checkout() || $this->mini_cart || ( is_admin() && ! wp_doing_ajax() ) ) :
			return $return;
		endif;

		$current_currency  = mcwc_get_selected_currency();
		$currencies        = $this->get_currency_list();
		$default_currency  = $this->get_default_currency();

		if ( $current_currency !== $default_currency ) :
			$original_price = mcwc_revert_price( $original_price, $current_currency );
		endif;

		$cache = [];
		foreach ( $currencies as $currency ) :
			$code         = $currency['currency'];
			$converted    = mcwc_get_price( $original_price, $code );
			$price_format = $this->get_price_format( $currency['position'] ?? 'left' );
			$decimals     = (int)( $currency['decimals'] ?? 2 );

			$cache[ $code ] = wc_price( $converted, [
				'currency'        => $code,
				'decimals'        => $decimals,
				'price_format'    => $price_format,
				'mcwc_cache'      => true,
			] );
        endforeach;

		$encoded = esc_attr( wp_json_encode( $cache ) );
		$tag = ( strpos( $return, '<div' ) !== false || strpos( $return, '<p' ) !== false ) ? 'div' : 'span';

		return sprintf(
			'<%1$s class="mcwc-json-price">%2$s<span class="mcwc-cache" style="display:none;" data-cache="%3$s"></span></%1$s>',
			$tag,
			$return,
			$encoded
		);
	}

	public function start_mini_cart() {
		$this->mini_cart = true;
	}

	public function end_mini_cart() {
		$this->mini_cart = false;
	}

	public function get_converted_prices() {
		check_ajax_referer( 'mcwc_nonce', 'nonce' );

		$pids               = isset( $_POST['pids'] ) ? array_map( 'absint', $_POST['pids'] ) : [];
		$current_currency   = mcwc_get_selected_currency();
		$currency_data      = $this->get_currency_data( $current_currency );

		$args = [
			'currency'     => $current_currency,
			'price_format' => $this->get_price_format( $currency_data['position'] ?? 'left' ),
			'decimals'     => (int)( $currency_data['decimals'] ?? 2 ),
		];

		$this->price_args = $args;
		add_filter( 'wc_price_args', [ $this, 'set_price_args' ], PHP_INT_MAX );

		$result = [];
		foreach ( $pids as $pid ) :
			$product = wc_get_product( $pid );
			if ( $product ) :
				$result[ $pid ] = $product->get_price_html();
			endif;
		endforeach;

		remove_filter( 'wc_price_args', [ $this, 'set_price_args' ], PHP_INT_MAX );
		$this->price_args = [];

		wp_send_json_success( [ 'prices' => $result ] );
	}

	public function set_price_args( $args ) {
		return array_merge( $args, $this->price_args );
	}

	protected function get_default_currency() {
		foreach ( $this->settings['currencies'] as $currency ) :
			if ( $currency['default'] === $currency['currency'] ) :
				return $currency['currency'];
            endif;
		endforeach;
		return 'USD'; // fallback
	}

	protected function get_currency_data( $code ) {
		foreach ( $this->settings['currencies'] as $currency ) :
			if ( $currency['currency'] === $code ) :
				return $currency;
            endif;
		endforeach;
		return [];
	}

	protected function get_currency_list() {
		return $this->settings['currencies'] ?? [];
	}

	protected function get_price_format( $pos ) {
		switch ( $pos ) :
			case 'left': return '%1$s%2$s';
			case 'right': return '%2$s%1$s';
			case 'left_space': return '%1$s&nbsp;%2$s';
			case 'right_space': return '%2$s&nbsp;%1$s';
			default: return '%1$s%2$s';
        endswitch;
	}
}
*/