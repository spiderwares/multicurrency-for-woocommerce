<?php
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'MCWC_Variable_Product_Metabox' ) ) :

	class MCWC_Variable_Product_Metabox {
		protected $settings;

		public function __construct() {

			$this->settings = get_option( 'mcwc_settings', array() );

            // Only add these fields if the 'fixed_price' setting is enabled
			if ( isset( $this->settings['fixed_price'] ) && $this->settings['fixed_price'] === 'yes' ) :
                // Hook to add the custom price fields to each variation
				add_action( 'woocommerce_variation_options_pricing', array( $this, 'variation_product_price_fields' ), 10, 3 );
            endif;

            // Hook to save the custom price fields when a variation is saved
			// add_action( 'woocommerce_save_product_variation', array( $this, 'save_variation_prices' ), 10, 2 );
		}

		/**
		 * Adds custom regular and sale price input fields for each currency
		 * to product variations.
		 *
		 * @param int    $loop           The loop counter for variations.
		 * @param array  $variation_data The variation data.
		 * @param object $variation      The WC_Product_Variation object.
		 */
		public function variation_product_price_fields( $loop, $variation_data, $variation ) {
            
			$currencies    	= isset( $this->settings['currencies'] ) ? $this->settings['currencies'] : [];
			$default    	= ( isset( $currencies[0] ) && isset( $currencies[0]['default'] ) ) ? $currencies[0]['default'] : '';

            // Retrieve existing multi-currency prices
			$regular_price_mcwc = json_decode( $variation->get_meta( '_regular_price_mcwc', true ), true );
			$sale_price_mcwc    = json_decode( $variation->get_meta( '_sale_price_mcwc', true ), true );

			foreach ( $currencies as $currency ) :
                // Skip the default currency as its price is handled by WooCommerce's default fields
				if ( $currency['currency'] === $default ) :
					continue;
                endif;

                $regular_price  = isset( $regular_price_mcwc[ $currency['currency'] ] ) ?  $regular_price_mcwc[ $currency['currency'] ] : '';
                $sale_price     = isset( $sale_price_mcwc[ $currency['currency'] ] ) ?  $sale_price_mcwc[ $currency['currency'] ] : ''; ?>

                <div>
					<p class="form-field">
						<label for="_regular_price_mcwc_<?php echo esc_attr( $currency['currency'] ); ?>">
							<?php echo esc_html__( 'Regular Price', 'multi-currency-woocommerce' ) . ' (' . esc_html( $currency['currency'] ) . ')'; ?>
						</label>
						<input type="text" class="short wc_input_price" name="_regular_price_mcwc[<?php echo esc_attr( $currency['currency'] ); ?>]" value="<?php //echo $regular_price; ?>">
					</p>
					<p class="form-field">
						<label for="_sale_price_mcwc_<?php echo esc_attr( $currency['currency'] ); ?>">
							<?php echo esc_html__( 'Sale Price', 'multi-currency-woocommerce' ) . ' (' . esc_html( $currency['currency'] ) . ')'; ?>
						</label>
						<input type="text" class="short wc_input_price" name="_sale_price_mcwc[<?php echo esc_attr( $currency['currency'] ); ?>]" value="<?php //echo $sale_price; ?>">
					</p>
				</div>
                
                <?php
			endforeach;
            // Add a clear div to ensure proper layout with floats
            echo '<div class="clear"></div>';
		}

		/**
		 * Saves the custom regular and sale prices for product variations.
		 *
		 * @param int $variation_id The ID of the variation being saved.
		 * @param int $loop         The loop counter for variations.
		 */
		public function save_variation_prices( $variation_id, $loop ) {
            // Fetch the variation product object
            $variation = wc_get_product( $variation_id );
            if ( ! $variation ) :
                return;
            endif;

            // Save regular prices
            if ( isset( $_POST['_regular_price_mcwc'][ $loop ] ) && is_array( $_POST['_regular_price_mcwc'][ $loop ] ) ) :
                $regular_prices = array_map( 'wc_format_decimal', $_POST['_regular_price_mcwc'][ $loop ] );
                $variation->update_meta_data( '_regular_price_mcwc', wp_json_encode( $regular_prices ) );
            else :
                // If no prices are submitted for this loop, remove the meta
                $variation->delete_meta_data( '_regular_price_mcwc' );
            endif;

            // Save sale prices
            if ( isset( $_POST['_sale_price_mcwc'][ $loop ] ) && is_array( $_POST['_sale_price_mcwc'][ $loop ] ) ) :
                $sale_prices = array_map( 'wc_format_decimal', $_POST['_sale_price_mcwc'][ $loop ] );
                $variation->update_meta_data( '_sale_price_mcwc', wp_json_encode( $sale_prices ) );
            else :
                // If no prices are submitted for this loop, remove the meta
                $variation->delete_meta_data( '_sale_price_mcwc' );
            endif;

            // Save the updated variation data
            $variation->save();
		}
	}

	new MCWC_Variable_Product_Metabox();

endif;