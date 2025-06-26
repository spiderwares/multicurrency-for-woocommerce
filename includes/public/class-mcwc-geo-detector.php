<?php
/**
 * Geo Currency Detection Class.
 *
 * Handles detection of user's country via WooCommerce GeoIP and sets currency.
 *
 * @package MultiCurrencyForWooCommerce
 */
if ( ! defined( 'ABSPATH' ) ) :
    exit; // Exit if accessed directly
endif;


if ( ! class_exists( 'MCWC_Geo_Currency' ) ) :

    /**
     * Class MCWC_Geo_Currency
     *
     * Handles automatic currency setting based on user location using WooCommerce GeoIP.
     */
    class MCWC_Geo_Currency {
        
        /**
         * Plugin settings array.
         *
         * @var array
         */
        private $settings;

        /**
         * MCWC_Geo_Currency constructor.
         * Loads plugin settings and hooks into init to detect currency.
         */
        public function __construct() {
            $this->settings = get_option( 'mcwc_settings', array() );
            add_action( 'wp_login', [ $this, 'set_currency_by_geo' ] );
        }

        /**
         * Detect country via WooCommerce and set currency in session or cookie.
         *
         * @return void
         */
        public function set_currency_by_geo() {

            $auto_detect = isset( $this->settings['auto_detect'] ) ? $this->settings['auto_detect'] : 'no';

            if ( $auto_detect !== 'woocommerce_geoip' ) :
                return;
            endif;

            if ( is_admin() || ! class_exists( 'WC_Geolocation' ) || ! WC()->session ) :
                return;
            endif;

            $geo_data     = WC_Geolocation::geolocate_ip();
            $country_code = isset( $geo_data['country'] ) ? $geo_data['country'] : '';
            
            if ( ! $country_code ) :
                return;
            endif;

            $currency = $this->get_currency_by_country( $country_code );
            
            if ( $currency ) :
                if ( isset( $this->settings['use_session'] ) && $this->settings['use_session'] === 'yes' ) :
                    if ( ! session_id() && ! headers_sent() ) :
                        session_start();
                    endif;
                    $_SESSION['mcwc_selected_currency'] = $currency;
                else :
                    setcookie( 'mcwc_selected_currency', $currency, time() + (30 * DAY_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN );
                endif;
            endif;
        }

        /**
         * Map country codes to currency codes.
         *
         * @param string $country_code ISO country code.
         * @return string Currency code or empty string if not found.
         */
        private function get_currency_by_country( $country ) {
			$currency = '';

            if ( ! empty( $this->settings['currency_by_countries'] ) && is_array( $this->settings['currency_by_countries'] ) ) :
				foreach ( $this->settings['currency_by_countries'] as $currency_code => $countries ) :
					if ( is_array( $countries ) && in_array( $country, $countries, true ) ) :
						$currency = $currency_code;
						break;
					endif;
				endforeach;
			endif;

			return $currency;
        }

    }

    // Initialize the class.
    new MCWC_Geo_Currency();

endif;