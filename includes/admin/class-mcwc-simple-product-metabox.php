<?php
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'MCWC_Simple_Product_Metabox' ) ) :

	class MCWC_Simple_Product_Metabox {

		protected $settings;

		public function __construct() {
			$this->settings = get_option( 'mcwc_settings', array() );

			if ( isset( $this->settings['fixed_price'] ) && $this->settings['fixed_price'] === 'yes' ) :
                add_action( 'woocommerce_product_options_pricing', array( $this, 'simple_product_price_fields' ) );
            endif;

			$product_types = apply_filters( 'mcwc_supported_product_types', [
				'simple', 'external', 'bundle', 'course', 'subscription', 'woosb', 'composite', 'appointment',
			] );

			foreach ( $product_types as $type ) :
				add_action( 'woocommerce_process_product_meta_' . $type, array( $this, 'save_simple_product_prices' ) );
            endforeach;
		}

		public function simple_product_price_fields() {
			global $post;
			$product       	= wc_get_product( $post->ID );
			$currencies    	= isset( $this->settings['currencies'] ) ? $this->settings['currencies'] : [];
			$default    	= ( isset( $currencies[0] ) && isset( $currencies[0]['default'] ) ) ? $currencies[0]['default'] : '';
			$regular_price 	= json_decode( $product->get_meta( '_regular_price_mcwc', true ), true );
			$sale_price    	= json_decode( $product->get_meta( '_sale_price_mcwc', true ), true );

			foreach ( $currencies as $currency ) :
				if ( $currency['currency'] === $default ) :
					continue;
                endif; ?>
				<div>
					<p class="form-field">
						<label for="_regular_price_mcwc_<?php echo esc_attr( $currency['currency'] ); ?>">
							<?php echo esc_html__( 'Regular Price', 'multicurrency-for-woocommerce' ) . ' (' . esc_html( get_woocommerce_currency_symbol( $currency['currency'] ) ) . ')'; ?>
						</label>
						<input type="text" class="short wc_input_price" name="_regular_price_mcwc[<?php echo esc_attr( $currency['currency'] ); ?>]" value="<?php echo esc_attr( isset( $regular_price[ $currency['currency'] ] ) ? $regular_price[ $currency['currency'] ] : '' ); ?>">
					</p>
					<p class="form-field">
						<label for="_sale_price_mcwc_<?php echo esc_attr( $currency['currency'] ); ?>">
							<?php echo esc_html__( 'Sale Price', 'multicurrency-for-woocommerce' ) . ' (' . esc_html( get_woocommerce_currency_symbol( $currency['currency'] ) ) . ')'; ?>
						</label>
						<input type="text" class="short wc_input_price" name="_sale_price_mcwc[<?php echo esc_attr( $currency['currency'] ); ?>]" value="<?php echo esc_attr( isset( $sale_price[ $currency['currency'] ] ) ? $sale_price[ $currency['currency'] ] : '' ); ?>">
					</p>
				</div>
				<?php
			endforeach;
			wp_nonce_field( 'mcwc_save_simple_price', '_mcwc_nonce' );
		}

        public function save_simple_product_prices( $post_id ) {
            // Permission and nonce check
            if ( ! current_user_can( 'manage_woocommerce' ) ) :
                return;
            endif;

            if ( ! isset( $_POST['_mcwc_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_mcwc_nonce'] ) ), 'mcwc_save_simple_price' ) ) :
                return;
            endif;

            // Fetch the product object
            $product = wc_get_product( $post_id );
            if ( ! $product ) :
                return;
            endif;

            // Sanitize and save regular prices
            if ( isset( $_POST['_regular_price_mcwc'] ) && is_array( $_POST['_regular_price_mcwc'] ) ) :
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                $regular_prices = array_map( 'wc_format_decimal', wp_unslash( $_POST['_regular_price_mcwc'] ) );
                $product->update_meta_data( '_regular_price_mcwc', wp_json_encode( $regular_prices ) );
            endif;

            // Sanitize and save sale prices
            if ( isset( $_POST['_sale_price_mcwc'] ) && is_array( $_POST['_sale_price_mcwc'] ) ) :
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                $sale_prices = array_map( 'wc_format_decimal', wp_unslash( $_POST['_sale_price_mcwc'] ));
                $product->update_meta_data( '_sale_price_mcwc', wp_json_encode( $sale_prices ) );
            endif;

            // Save the updated meta
            $product->save();
        }

		private function decode_price_meta( $meta ) {
			return is_string( $meta ) ? json_decode( $meta, true ) : $meta;
		}
		
	}

	new MCWC_Simple_Product_Metabox();

endif;