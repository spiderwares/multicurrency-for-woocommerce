<?php
/**
 * Adds custom price fields per currency to WooCommerce product variations
 * and handles saving those fields.
 *
 * @package MultiCurrencyForWooCommerce
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'MCWC_Variable_Product_Metabox' ) ) :

	/**
	 * Class MCWC_Variable_Product_Metabox
	 *
	 * Handles custom regular and sale price fields for each variation
	 * based on configured currencies.
	 */
	class MCWC_Variable_Product_Metabox {

		/**
		 * Plugin settings from mcwc_settings option.
		 *
		 * @var array
		 */
		protected $settings;

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->settings = get_option( 'mcwc_settings', array() );

			if ( isset( $this->settings['fixed_price'] ) && $this->settings['fixed_price'] === 'yes' ) :
				add_action( 'woocommerce_variation_options_pricing', array( $this, 'variation_product_price_fields' ), 10, 3 );
			endif;

			add_action( 'woocommerce_save_product_variation', array( $this, 'save_variation_prices' ), 10, 2 );
		}

		/**
		 * Adds custom regular and sale price input fields for each currency to product variations.
		 *
		 * @param int    $loop           The loop counter for variations.
		 * @param array  $variation_data The variation data.
		 * @param object $variation      The WC_Product_Variation object.
		 */
		public function variation_product_price_fields( $loop, $variation_data, $variation ) {
			$currencies    = isset( $this->settings['currencies'] ) ? $this->settings['currencies'] : array();
			$default       = ( isset( $currencies[0] ) && isset( $currencies[0]['default'] ) ) ? $currencies[0]['default'] : '';
			$variation_id  = isset( $variation->ID ) ? $variation->ID : $variation->get_id();

			$regular_prices = json_decode( get_post_meta( $variation_id, '_regular_price_mcwc', true ), true );
			$sale_prices    = json_decode( get_post_meta( $variation_id, '_sale_price_mcwc', true ), true );

			foreach ( $currencies as $currency ) :
				if ( $currency['currency'] === $default ) :
					continue; // Skip default currency; handled by WooCommerce core.
				endif;

				$curr_code     = $currency['currency'];
				$regular_price = isset( $regular_prices[ $curr_code ] ) ? $regular_prices[ $curr_code ] : '';
				$sale_price    = isset( $sale_prices[ $curr_code ] ) ? $sale_prices[ $curr_code ] : ''; ?>

				<div class="mcwc-variation-field-wrapper">
					<p class="form-field">
						<label for="variation_<?php echo esc_attr( $variation_id ); ?>_regular_price_mcwc_<?php echo esc_attr( $curr_code ); ?>">
							<?php echo esc_html__( 'Regular Price', 'multicurrency-for-woocommerce' ) . ' (' . esc_html( $curr_code ) . ')'; ?>
						</label>
						<input type="text" class="short wc_input_price" name="variation_<?php echo esc_attr( $variation_id ); ?>[_regular_price_mcwc][<?php echo esc_attr( $curr_code ); ?>]" value="<?php echo esc_attr( $regular_price ); ?>">
					</p>
					<p class="form-field">
						<label for="variation_<?php echo esc_attr( $variation_id ); ?>_sale_price_mcwc_<?php echo esc_attr( $curr_code ); ?>">
							<?php echo esc_html__( 'Sale Price', 'multicurrency-for-woocommerce' ) . ' (' . esc_html( $curr_code ) . ')'; ?>
						</label>
						<input type="text" class="short wc_input_price" name="variation_<?php echo esc_attr( $variation_id ); ?>[_sale_price_mcwc][<?php echo esc_attr( $curr_code ); ?>]" value="<?php echo esc_attr( $sale_price ); ?>">
					</p>
				</div>

				<?php
			endforeach;
			// Clear layout if needed.
			echo '<div class="clear"></div>';
		}

		/**
		 * Saves the custom regular and sale prices for product variations.
		 *
		 * @param int $variation_id The variation ID being saved.
		 * @param int $i            The index in the variations loop (unused).
		 */
		public function save_variation_prices( $variation_id, $i ) {
			$variation = wc_get_product( $variation_id );
			if ( ! $variation ) :
				return;
			endif;

			$post_key = 'variation_' . $variation_id;
			$data     = isset( $_POST[ $post_key ] ) ? $_POST[ $post_key ] : array();

			// Save regular prices.
			if ( isset( $data['_regular_price_mcwc'] ) && is_array( $data['_regular_price_mcwc'] ) ) :
				$regular_prices = array_map( 'wc_format_decimal', $data['_regular_price_mcwc'] );
				$variation->update_meta_data( '_regular_price_mcwc', wp_json_encode( $regular_prices ) );
			else :
				$variation->delete_meta_data( '_regular_price_mcwc' );
			endif;

			// Save sale prices.
			if ( isset( $data['_sale_price_mcwc'] ) && is_array( $data['_sale_price_mcwc'] ) ) :
				$sale_prices = array_map( 'wc_format_decimal', $data['_sale_price_mcwc'] );
				$variation->update_meta_data( '_sale_price_mcwc', wp_json_encode( $sale_prices ) );
			else:
				$variation->delete_meta_data( '_sale_price_mcwc' );
			endif;

			$variation->save();
		}
	}

	// Initialize the metabox functionality.
	new MCWC_Variable_Product_Metabox();

endif;
