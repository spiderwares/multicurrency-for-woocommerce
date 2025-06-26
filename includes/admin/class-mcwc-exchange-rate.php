<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'MCWC_Exchange_Rate' ) ) :

	/**
	 * Class MCWC_Exchange_Rate
	 *
	 * Handles AJAX exchange rate retrieval and finance API integration for MultiCurrency.
	 */
	class MCWC_Exchange_Rate {

		/**
		 * Plugin settings array.
		 *
		 * @var array
		 */
		public $setting;

		/**
		 * Currency API handler object (Pro only).
		 *
		 * @var object|null
		 */
		public $api_handler = null;

		/**
		 * Constructor.
		 * Initializes settings and hooks.
		 */
		public function __construct() {
			$this->setting = get_option( 'mcwc_settings', true );

			// Only use Pro handler if class exists
			if ( class_exists( 'MCWC_PRO_Currency_API_Handler' ) ) :
				$this->api_handler = new MCWC_PRO_Currency_API_Handler();
			endif;

			// Register AJAX action for retrieving exchange rates.
			add_action( 'wp_ajax_mcwc_get_exchange_rates', [ $this, 'get_exchange_rates' ] );
		}

		/**
		 * Get finance API from filter hook.
		 *
		 * @return mixed Filtered finance API value.
		 */
		public function fetch_finance_api() {
			return apply_filters( 'mcwc_fetch_finance_api', 'google' );
		}
		
		public function fetch_rate_decimals() {
			$decimals = isset( $this->setting['rate_decimals'] ) && ! empty( $this->setting['rate_decimals'] )
						? (int) $this->setting['rate_decimals']
						: 5;

			return (int) apply_filters( 'mcwc_fetch_rate_decimals', $decimals );
		}

		/**
		 * Handle AJAX request to get exchange rates for selected currencies.
		 *
		 * @return void Outputs JSON success or error response.
		 */
		public function get_exchange_rates() {

			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'mcwc_currency_nonce' ) ) :
                return;
            endif;

			$default_currency = isset( $_POST['default_currency'] ) ? sanitize_text_field( $_POST['default_currency'] ) : '';
			$other_currencies = isset( $_POST['other_currencies'] ) ? explode( ',', sanitize_text_field( $_POST['other_currencies'] ) ) : [];

			if ( empty( $default_currency ) || empty( $other_currencies ) ) :
				wp_send_json_error( [ 'message' => 'Missing currency data' ] );
			endif;

			$selected_api = $this->fetch_finance_api();
			$rates_data   = [];

			switch ( $selected_api ) :
				case 'google':
					$rates_data = $this->fetch_google_exchange_rates( $default_currency, $other_currencies );
					break;
				case 'yahoo':
					$rates_data = $this->api_handler->fetch_yahoo_exchange_rates( $default_currency, $other_currencies );
					break;
				case 'cuex':
					$rates_data = $this->api_handler->fetch_cuex_exchange_rates( $default_currency, $other_currencies );
					break;
				case 'wise':
					$rates_data = $this->api_handler->fetch_wise_exchange_rates( $default_currency, $other_currencies );
					break;
				case 'xe':
					$rates_data = $this->api_handler->fetch_xe_exchange_rates( $default_currency, $other_currencies );
					break;
				case 'openexchangerates':
					$rates_data = $this->api_handler->fetch_open_exchange_rates( $default_currency, $other_currencies );
					break;
				case 'exchangerateapi':
					$rates_data = $this->api_handler->fetch_exchangeratesapi_rates( $default_currency, $other_currencies );
					break;
				case 'currencyapi':
					$rates_data = $this->api_handler->fetch_currencyapi_rates( $default_currency, $other_currencies );
					break;
				default:
					$rates_data = apply_filters(
						'mcwc_fetch_custom_exchange_rates',
						$rates_data,
						$default_currency,
						$other_currencies,
						$this
					);
					break;
			endswitch;

			if ( empty( $rates_data ) || ! is_array( $rates_data ) ) :
				wp_send_json_error( [ 'message' => 'Failed to retrieve exchange rates' ] );
			endif;

			// Apply rate decimals
			$decimal = $this->fetch_rate_decimals();
			foreach ( $rates_data as $code => $rate ) :
				$rates_data[ $code ] = round( (float) $rate, $decimal );
			endforeach;

			do_action( 'mcwc_exchange_rates_updated', $rates_data, $default_currency, $other_currencies, $this );
			
			wp_send_json_success( $rates_data );
		}

		/**
		 * @param $default_currency
		 * @param $other_currencys
		 *
		 * @return array|bool
		 */
		public function fetch_google_exchange_rates( $default_currency, $other_currencys ) {
			
			require_once MCWC_PATH . 'includes/static/currency-country-map.php';	
			$currency_rates = [];
		
			foreach ( $other_currencys as $currency_code ) :

				$currency_rates[ $currency_code ] = false;
				$url            = 'https://www.google.com/async/currency_v2_update?vet=12ahUKEwjfsduxqYXfAhWYOnAKHdr6BnIQ_sIDMAB6BAgFEAE..i&ei=kgAGXN-gDJj1wAPa9ZuQBw&yv=3&async=source_amount:1,source_currency:' . MCWC_Currency_Country_Map::get_freebase_id( $default_currency ) . ',target_currency:' . MCWC_Currency_Country_Map::get_freebase_id( $currency_code ) . ',lang:en,country:us,disclaimer_url:https%3A%2F%2Fwww.google.com%2Fintl%2Fen%2Fgooglefinance%2Fdisclaimer%2F,period:5d,interval:1800,_id:knowledge-currency__currency-v2-updatable,_pms:s,_fmt:pc';

				$request = wp_remote_get(
					$url, array(
						'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
						'timeout'    => 10
					)
				);

				if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) :
					preg_match( '/data-exchange-rate=\"(.+?)\"/', $request['body'], $match );
					if ( sizeof( $match ) > 1 && $match[1] ) :
						$currency_rates[ $currency_code ] = $match[1];
					endif;
				endif;
			endforeach;

			return $currency_rates;
		}

	}

	// Instantiate the class.
	new MCWC_Exchange_Rate();

endif;
