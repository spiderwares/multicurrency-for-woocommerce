<?php
defined('ABSPATH') || exit;

if( ! class_exists( 'MCWC_Admin_Settings' ) ):

    /**
     * Class MCWC_Admin_Settings
     * Handles the admin settings for the free shipping bar in WooCommerce.
     */
    class MCWC_Admin_Settings {

        /**
         * Generates the general settings fields for the shipping bar.
         *
         * @return array The settings fields for the general configuration.
         */
        public static function general_field() {
            $fields = array(
                'enable'    => array(
                    'title'         => esc_html__( 'Enable', 'multicurrency-for-woocommerce' ),
                    'field_type'    => 'mcwcswitch',
                    'default'       => 'yes',
                    'name'          => 'mcwc_settings[enable]',
                ),

                'fixed_price'   => array(
                    'title'         => esc_html__( 'Fixed Price', 'multicurrency-for-woocommerce' ),
                    'field_type'    => 'mcwcswitch',
                    'default'       => 'yes',
                    'name'          => 'mcwc_settings[fixed_price]',
                    'desc'          => esc_html__( 'Set up product price in each currency manually. This price will overwrite the calculated price.', 'multicurrency-for-woocommerce' ),
                ),

                'use_session'   => array(
                    'title'         => esc_html__( 'Use SESSION', 'multicurrency-for-woocommerce' ),
                    'field_type'    => 'mcwcbuypro',
                    'pro_link'      => MCWC_PRO_VERSION_URL,
                    'default'       => 'no',
                ),

                'switch_currency_by_js' => array(
                    'title'         => esc_html__( 'Switch Currency by JS', 'multicurrency-for-woocommerce' ),
                    'field_type'    => 'mcwcbuypro',
                    'pro_link'      => MCWC_PRO_VERSION_URL,
                    'default'       => 'no',
                ),

                'use_cache' => array(
                    'title'       => esc_html__( 'Caching Compatibility Mode', 'multicurrency-for-woocommerce' ),
                    'field_type'  => 'mcwcselect',
                    'name'        => 'mcwc_settings[use_cache]',
                    'default'     => 'none',
                    'options'     => array(
                        'none'              => esc_html__( 'None (No Caching Support Needed)', 'multicurrency-for-woocommerce' ),
                        'ajax_override'     => esc_html__( 'Override Prices via AJAX (Recommended)', 'multicurrency-for-woocommerce' ),
                        'json_override'     => esc_html__( 'Override Prices via JSON (for Polylang or Custom Cache Handling)', 'multicurrency-for-woocommerce' ),
                    ),
                    'desc'        => esc_html__(
                        'Enable this option if your site uses a page caching plugin (e.g., WP Super Cache, W3 Total Cache, WP Rocket) and the selected currency is not retained after switching. '
                        . 'Choose "AJAX" to reload prices dynamically, or "JSON" if you are using Polylang or other tools that require static content rewriting.',
                        'multicurrency-for-woocommerce'
                    ),
                ),

                'currency_option_title' => array(
                    'title'         => esc_html__('Currency Options', 'multicurrency-for-woocommerce'),
                    'field_type'    => 'mcwctitle',
                    'default'       => '',
                ),

                'currencies' => array(
                    'title'         => esc_html__('Currency Table', 'multicurrency-for-woocommerce'),
                    'field_type'    => 'mcwccurrencyoption',
                    'name'          => 'mcwc_settings[currencies]',
                    'desc'          => esc_html__('Manage multiple currencies, their rates, symbols, and formatting.', 'multicurrency-for-woocommerce'),
                    'default'       => array(), // default could include a sample entry if needed
                ),

            );

            // Apply filter to allow modifications to the general fields.
            return apply_filters( 'mcwc_general_fields', $fields );
        }

        /**
         * Generates the position settings fields for the shipping bar.
         *
         * @return array The settings fields for the position configuration.
         */
        public static function location_field() {

            $currencies = self::configure_currencies();

            $fields = array(

                'auto_detect' => array(
                    'title'      => esc_html__( 'Auto Detect', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcselect',
                    'name'       => 'mcwc_settings[auto_detect]',
                    'default'    => 'no',
                    'data_hide'  => '.auto_detect_option',
                    'options'    => array(
                        'no'                 => esc_html__( 'No', 'multicurrency-for-woocommerce' ),
                        'auto_select'        => esc_html__( 'Auto select currency', 'multicurrency-for-woocommerce' ),
                        'woocommerce_geoip'  => esc_html__( 'WooCommerce GeoIP', 'multicurrency-for-woocommerce' ),
                        'polylang'           => esc_html__( 'Language Polylang', 'multicurrency-for-woocommerce' ),
                        'approximate_price'  => esc_html__( 'Approximate Price', 'multicurrency-for-woocommerce' ),
                        'TranslatePress'     => esc_html__( 'TranslatePress Multilingual (Premium)', 'multicurrency-for-woocommerce' ),
                    ),
                    'disabled_options' => array( 'TranslatePress', 'approximate_price' ),
                ),

                'switch_user_login' => array(
                    'title'         => esc_html__( 'Switch when user login', 'multicurrency-for-woocommerce' ),
                    'field_type'    => 'mcwcbuypro',
                    'pro_link'      => MCWC_PRO_VERSION_URL,
                    'default'       => 'no',
                ),

                'geo_api' => array(
                    'title'      => esc_html__( 'Geo API', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcselect',
                    'name'       => 'mcwc_settings[geo_api]',
                    'default'    => 'no',
                    'options'    => array(
                        'woocommerce'       => esc_html__( 'WooCommerce', 'multicurrency-for-woocommerce' ),
                        'external'          => esc_html__( 'External', 'multicurrency-for-woocommerce' ),
                        'polylang'          => esc_html__( 'Inherited from server (Premium)', 'multicurrency-for-woocommerce' ),
                        'TranslatePress'    => esc_html__( 'MaxMind Geolocation (Premium)', 'multicurrency-for-woocommerce' ),
                    ),
                    'desc'              => esc_html__( 'API will help detect customer country code base on IP address.', 'multicurrency-for-woocommerce' ),
                    'disabled_options'  => array('polylang', 'TranslatePress',),
                ),

                'currency_by_country'   => array(
                    'title'         => esc_html__( 'Currency by Country', 'multicurrency-for-woocommerce' ),
                    'field_type'    => 'mcwcswitch',
                    'default'       => 'yes',
                    'name'          => 'mcwc_settings[currency_by_country]',
                    'data_show'     => '.currency_by_country_option',
                    'desc'          => esc_html__( 'Only working with AUTO SELECT CURRENCY feature. Currency will be selected base on country.', 'multicurrency-for-woocommerce' ),
                ),

                'currency_by_countries' => array(
                    'title'         => esc_html__( 'Switch when user login', 'multicurrency-for-woocommerce-pro' ),
                    'field_type'    => 'mcwc_currency_by_countries',
                    'name'          => 'mcwc_settings[currency_by_countries]',
                    'default'       => '',
                    'currencies'    => $currencies,
                    'style'         => 'currency_by_country.yes',
                    'extra_class'   => 'currency_by_country_option',
                ),

            );

            // Apply filter to allow modifications to the position fields.
            return apply_filters( 'mcwc_location_fields', $fields );
        }     

        /**
         * Generates the checkout-related settings fields.
         *
         * @return array The settings fields for the checkout configuration.
         */
        public static function checkout_field() {
            $fields = array(
                
                'enable_multi_payment' => array(
                    'title'      => esc_html__( 'Enable', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcswitch',
                    'default'    => 'yes',
                    'name'       => 'mcwc_settings[enable_multi_payment]',
                    'desc'       => esc_html__( 'Pay in many currencies.', 'multicurrency-for-woocommerce' ),
                ),
                'enable_cart_page' => array(
                    'title'      => esc_html__( 'Enable Cart Page', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcbuypro',
                    'pro_link'   => MCWC_PRO_VERSION_URL,
                    'default'    => 'no',
                    'desc'       => esc_html__( 'Change the currency in cart page to a check out currency.', 'multicurrency-for-woocommerce' ),
                ),
                'checkout_currency' => array(
                    'title'      => esc_html__( 'Checkout Currency', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcbuypro',
                    'pro_link'   => MCWC_PRO_VERSION_URL,
                    'default'    => 'no',
                    'desc'       => wp_kses_post( '<div class="mcwc-description-box">Payment method depend on Payment Gateway. If Payment Gateway is not support currency, customer can not checkout with currency. Example: Paypal is not support IDR, Customer can not checkout IDR by Paypal.</div>', 'multicurrency-for-woocommerce' ),
                ),
                'currency_by_payment_method' => array(
                    'title'      => esc_html__( 'Currency by Payment Method', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcbuypro',
                    'pro_link'   => MCWC_PRO_VERSION_URL,
                    'default'    => 'no',
                    'desc'       => esc_html__( 'Enable this option to change the currency immediately at the checkout order detail when the customer selects a payment gateway, instead of after clicking the Place order button.', 'multicurrency-for-woocommerce' ),
                ),
                'change_currency_follow' => array(
                    'title'      => esc_html__( 'Change Currency Follow', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcbuypro',
                    'pro_link'   => MCWC_PRO_VERSION_URL,
                    'default'    => 'no',
                    'desc'       => esc_html__( 'Change currency when customer change billing or shipping address.', 'multicurrency-for-woocommerce' ),
                ),

            );

            // Apply filter to allow modifications to the checkout fields.
            return apply_filters( 'mcwc_checkout_fields', $fields );
        }


        /**
         * Generates the design-related settings fields (e.g., currency bar visibility).
         *
         * @return array The settings fields for the design tab.
         */
        public static function design_fields() {

            $hookOptions    = require MCWC_PATH . 'includes/static/single-product-hooks.php';
            $current_theme  = wp_get_theme();
            $theme 		    = $current_theme->get_template();
            $single_hooks   = isset( $theme, $hookOptions[$theme] ) ? $hookOptions[$theme] : $hookOptions['default'];

            $fields = array(
                'switcher_title' => array(
                    'title'      => esc_html__( 'Switcher Title', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwctext',
                    'name'       => 'mcwc_settings[switcher_title]',
                    'default'    => esc_html__( 'Select your currency', 'multicurrency-for-woocommerce' ),
                ),

                'design_position' => array(
                    'title'      => esc_html__( 'Position', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcselect',
                    'name'       => 'mcwc_settings[design_position]',
                    'default'    => 'right',
                    'options'    => array(
                        'left'  => esc_html__( 'Left', 'multicurrency-for-woocommerce' ),
                        'right' => esc_html__( 'Right', 'multicurrency-for-woocommerce' ),
                    ),
                    'desc'       => esc_html__( 'Select the position where the currency switcher will appear. Left or Right aligned on the screen.', 'multicurrency-for-woocommerce' ),
                ),

                'enable_desktop' => array(
                    'title'      => esc_html__( 'Desktop', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcswitch',
                    'name'       => 'mcwc_settings[enable_desktop]',
                    'default'    => 'no',
                    'desc'       => esc_html__( 'Sidebar will collapse if you have many currencies.', 'multicurrency-for-woocommerce' ),
                ),

                // 'enable_click_to_expand_currency_bar' => array(
                //     'title'      => esc_html__( 'Click to expand currencies bar', 'multicurrency-for-woocommerce' ),
                //     'field_type' => 'mcwcbuypro',
                //     'pro_link'   => MCWC_PRO_VERSION_URL,
                //     'default'    => 'no',
                //     'desc'       => esc_html__( 'By default, currencies bar will expand on hovering. Enable this option if you want them to only expand when clicking on.', 'multicurrency-for-woocommerce' ),
                // ),

                'expand_button_color' => array(
                    'title'      => esc_html__( 'Expand button color', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcbuypro',
                    'pro_link'   => MCWC_PRO_VERSION_URL,
                    'default'    => '',
                ),

                'expand_button_bg' => array(
                    'title'      => esc_html__( 'Expand button background', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcbuypro',
                    'pro_link'   => MCWC_PRO_VERSION_URL,
                    'default'    => '',
                ),

                'expand_button_opacity' => array(
                    'title'      => esc_html__( 'Expand button opacity', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcbuypro',
                    'pro_link'   => MCWC_PRO_VERSION_URL,
                    'default'    => '',
                ),

                'text_color' => array(
                    'title'       => esc_html__( 'Text Color', 'multicurrency-for-woocommerce' ),
                    'field_type'  => 'mcwccolor',
                    'name'        => 'mcwc_settings[text_color]',
                    'default'     => '#ffffff',
                ),

                'sidebar_style' => array(
                    'title'         => esc_html__( 'Sidebar Style', 'multicurrency-for-woocommerce' ),
                    'field_type'    => 'mcwcselect',
                    'name'          => 'mcwc_settings[sidebar_style]',
                    'default'       => 'default',
                    'options'       => array(
                        'default'       => esc_html__( 'Default', 'multicurrency-for-woocommerce' ),
                        'symbol'        => esc_html__( 'Symbol', 'multicurrency-for-woocommerce' ),
                        'flag'          => esc_html__( 'Flag', 'multicurrency-for-woocommerce' ),
                        'flag_code'     => esc_html__( 'Flag + Currency code', 'multicurrency-for-woocommerce' ),
                        'flag_symbol'   => esc_html__( 'Flag + Currency symbol', 'multicurrency-for-woocommerce' ),
                    ),
                ),

                'hover_color' => array(
                    'title'       => esc_html__( 'Hover/Active Color', 'multicurrency-for-woocommerce' ),
                    'field_type'  => 'mcwccolor',
                    'name'        => 'mcwc_settings[hover_color]',
                    'default'     => '#e67e00',
                ),

                'background_color' => array(
                    'title'       => esc_html__( 'Background Color', 'multicurrency-for-woocommerce' ),
                    'field_type'  => 'mcwccolor',
                    'name'        => 'mcwc_settings[background_color]',
                    'default'     => '#dd9933',
                ),

                'product_currency_selector' => array(
                    'title'         => esc_html__('Product currency selector', 'multicurrency-for-woocommerce'),
                    'field_type'    => 'mcwctitle',
                    'default'       => '',
                ),

                'single_switcher_style' => array(
                    'title'      => esc_html__( 'Currency Price Switcher', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcselect',
                    'name'       => 'mcwc_settings[single_switcher_style]',
                    'default'    => 'no',
                    'options'    => array(
                        'no'            => esc_html__( 'Not Show', 'multicurrency-for-woocommerce' ),
                        'flag'          => esc_html__( 'Flag', 'multicurrency-for-woocommerce' ),
                        'flag_code'     => esc_html__( 'Flag + Currency Code', 'multicurrency-for-woocommerce' ),
                        'flag_symbol'   => esc_html__( 'Flag + Symbol', 'multicurrency-for-woocommerce' ),
                        'flag_price'    => esc_html__( 'Flag + Price', 'multicurrency-for-woocommerce' ),
                    ),
                    'desc'       => esc_html__( 'Display a currency switcher under product price in single product pages.', 'multicurrency-for-woocommerce' ),
                ),

                'switcher_layout' => array(
                    'title'      => esc_html__( 'Switcher layout', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcbuypro',
                    'pro_link'   => MCWC_PRO_VERSION_URL,
                    'default'    => '',
                ),

                'single_position' => array(
                    'title'      => esc_html__( 'Switcher Position', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcselect',
                    'name'       => 'mcwc_settings[single_position]',
                    'default'    => 'woocommerce_single_product_summary-20',
                    'options'    => $single_hooks,
                    'desc'       => esc_html__( 'Position of currency switcher in single product pages, it may be affected by the theme or product template', 'multicurrency-for-woocommerce' ),
                ),

                // 'enable_click_to_expand_currency_selector' => array(
                //     'title'      => esc_html__( 'Click to expand currency selector', 'multicurrency-for-woocommerce' ),
                //     'field_type' => 'mcwcbuypro',
                //     'pro_link'   => MCWC_PRO_VERSION_URL,
                //     'default'    => 'no',
                //     'desc'       => esc_html__( 'By default, dropdown currency selector will expand on hovering. Enable this option if you want them to only expand when clicking on.', 'multicurrency-for-woocommerce' ),
                // ),

                'product_currency_selector_shortcode' => array(
                    'title'         => esc_html__('Single Product currency Switcher', 'multicurrency-for-woocommerce'),
                    'field_type'    => 'mcwctitle',
                    'default'       => '',
                ),

                'shortcode_color' => array(
                    'title'       => esc_html__( 'Text color', 'multicurrency-for-woocommerce' ),
                    'field_type'  => 'mcwccolor',
                    'name'        => 'mcwc_settings[shortcode_color]',
                    'default'     => '#212121',
                ),

                'shortcode_bg_color' => array(
                    'title'       => esc_html__( 'Background color', 'multicurrency-for-woocommerce' ),
                    'field_type'  => 'mcwccolor',
                    'name'        => 'mcwc_settings[shortcode_bg_color]',
                    'default'     => '#ffffff',
                ),

                'shortcode_active_color' => array(
                    'title'       => esc_html__( 'Active currency text color', 'multicurrency-for-woocommerce' ),
                    'field_type'  => 'mcwccolor',
                    'name'        => 'mcwc_settings[shortcode_active_color]',
                    'default'     => '#212121',
                ),

                'shortcode_active_bg_color' => array(
                    'title'       => esc_html__( 'Active currency background color', 'multicurrency-for-woocommerce' ),
                    'field_type'  => 'mcwccolor',
                    'name'        => 'mcwc_settings[shortcode_active_bg_color]',
                    'default'     => '#ffffff',
                ),

                'widget' => array(
                    'title'         => esc_html__('Widget', 'multicurrency-for-woocommerce'),
                    'field_type'    => 'mcwctitle',
                    'default'       => '',
                ),

                'custom_flag' => array(
                    'title'       => esc_html__( 'Custom Flag', 'multicurrency-for-woocommerce' ),
                    'field_type'  => 'mcwctextarea',
                    'name'        => 'mcwc_settings[custom_flag]',
                    'placeholder' => "Example:\nEUR,es\nUSD,vn",
                    'default'     => '',
                    'rows'        => '6',
                    'desc'        => esc_html__( 'Some countries use the same currency. You can choose the correct flag. Each line is the flag. Structure [currency_code,country_code]. Example: EUR,es', 'multicurrency-for-woocommerce' ),
                ),

                'custom' => array(
                    'title'         => esc_html__('Custom', 'multicurrency-for-woocommerce'),
                    'field_type'    => 'mcwctitle',
                    'default'       => '',
                ),

                'rel_nofollow' => array(
                    'title'         => esc_html__( 'Use rel="nofollow"', 'multicurrency-for-woocommerce' ),
                    'field_type'    => 'mcwcswitch',
                    'name'          => 'mcwc_settings[rel_nofollow]',
                    'default'       => '',
                    'desc'          => esc_html__( 'Enable this if you want rel="nofollow" to be added to currency switcher buttons', 'multicurrency-for-woocommerce' ),
                ),

                'custom_css' => array(
                    'title'         => esc_html__( 'CSS', 'multicurrency-for-woocommerce' ),
                    'field_type'    => 'mcwctextarea',
                    'name'          => 'mcwc_settings[custom_css]',
                    'default'       => '',
                    'rows'          => '6',
                    'placeholder'   => '.woo-multi-currency{}',
                ),


            );

            return apply_filters( 'mcwc_design_fields', $fields );
        }

        /**
         * Generates the Price Foramat Related settings fields.
         *
         * @return array The settings fields for the Price Foramat configuration.
         */
        public static function price_format_field() {
            $fields = array(

                'enable_price_format' => array(
                    'title'      => esc_html__( 'Enable', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcbuypro',
                    'pro_link'   => MCWC_PRO_VERSION_URL,
                    'default'    => 'no',
                    'desc'       => esc_html__( 'This option only works when input price same as output price (both include tax or exclude tax)', 'multicurrency-for-woocommerce' ),
                ),

            );

            // Apply filter to allow modifications to the Price Format fields.
            return apply_filters( 'mcwc_price_format_field', $fields );
        }

        /**
         * Generates the Update Related settings fields.
         *
         * @return array The settings fields for the Update configuration.
         */
        public static function update_field() {
            $fields = array(

                'auto_update_exchange_rate' => array(
                    'title'      => esc_html__( 'Auto Update Exchange Rate', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcbuypro',
                    'pro_link'   => MCWC_PRO_VERSION_URL,
                    'default'    => 'no',
                    'desc'       => esc_html__( 'Exchange will be updated automatically.', 'multicurrency-for-woocommerce' ),
                ),

                'finance_api' => array(
                    'title'      => esc_html__( 'Finance API', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcbuypro',
                    'pro_link'   => MCWC_PRO_VERSION_URL,
                    'default'    => 'no',
                    'desc'       => esc_html__( 'Exchange rate resources.', 'multicurrency-for-woocommerce' ),
                ),

                'rate_decimals' => array(
                    'title'      => esc_html__( 'Rate Decimals', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcnumber',
                    'default'    => '5',
                    'name'       => 'mcwc_settings[rate_decimals]',
                    'desc'       => esc_html__( 'Number of decimal places for exchange rates.', 'multicurrency-for-woocommerce' ),
                ),

                'enable_send_email' => array(
                    'title'      => esc_html__( 'Send Email', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcbuypro',
                    'pro_link'   => MCWC_PRO_VERSION_URL,
                    'default'    => 'no',
                    'desc'       => esc_html__( 'Send email when exchange rate is updated.', 'multicurrency-for-woocommerce' ),
                ),

                'custom_email' => array(
                    'title'      => esc_html__( 'Custom Email', 'multicurrency-for-woocommerce' ),
                    'field_type' => 'mcwcbuypro',
                    'pro_link'   => MCWC_PRO_VERSION_URL,
                    'default'    => '',
                    'desc'       => esc_html__( 'If empty, notification will be sent to i.harshil8493@gmail.com.', 'multicurrency-for-woocommerce' ),
                ),

            );

            // Apply filter to allow modifications to the update fields.
            return apply_filters( 'mcwc_update_fields', $fields );
        }

        /**
         * Generates the dispaly rute settings fields for the shipping bar.
         *
         * @return array The settings fields for the dispaly rute configuration.
         */  
        public static function display_rules_field() {
            $fields = array(
                'hide_on_home' => array(
                    'title'         => esc_html__('Hide on Home Page', 'essential-kit-for-woocommerce'),
                    'field_type'    => 'mcwcswitch',
                    'default'       => 'no',
                    'name'          => 'mcwc_settings[hide_on_home]',
                ),
                'hide_on_shop' => array(
                    'title'         => esc_html__('Hide on Shop Page', 'essential-kit-for-woocommerce'),
                    'field_type'    => 'mcwcswitch',
                    'default'       => 'no',
                    'name'          => 'mcwc_settings[hide_on_shop]',
                ),
                'hide_on_single_product' => array(
                    'title'         => esc_html__('Hide on Single Product Page', 'essential-kit-for-woocommerce'),
                    'field_type'    => 'mcwcswitch',
                    'default'       => 'no',
                    'name'          => 'mcwc_settings[hide_on_single_product]',
                ),
                'hide_on_cart' => array(
                    'title'         => esc_html__('Hide on Cart Page', 'essential-kit-for-woocommerce'),
                    'field_type'    => 'mcwcswitch',
                    'default'       => 'no',
                    'name'          => 'mcwc_settings[hide_on_cart]',
                ),
                'hide_on_checkout' => array(
                    'title'         => esc_html__('Hide on Checkout Page', 'essential-kit-for-woocommerce'),
                    'field_type'    => 'mcwcswitch',
                    'default'       => 'no',
                    'name'          => 'mcwc_settings[hide_on_checkout]',
                ),
                'hide_on_product_category' => array(
                    'title'         => esc_html__('Hide on Product Category Page', 'essential-kit-for-woocommerce'),
                    'field_type'    => 'mcwcswitch',
                    'default'       => 'no',
                    'name'          => 'mcwc_settings[hide_on_product_category]',
                ),
                'conditional_tags' => array(
                    'title'       => esc_html__( 'Conditional Tags', 'multicurrency-for-woocommerce' ),
                    'field_type'  => 'mcwctext',
                    'name'        => 'mcwc_settings[conditional_tags]',
                    'default'     => '',
                    'placeholder' => esc_html__( 'e.g. !is_page(array(34,98,73))', 'multicurrency-for-woocommerce' ),
                    'desc'        => esc_html__( 'Adjust which pages will appear using WP\'s conditional tags. Ex: is_home(), is_shop(), is_product(), is_cart(), is_checkout().', 'multicurrency-for-woocommerce' ) .
                                    ' <a href="https://codex.wordpress.org/Conditional_Tags" target="_blank">' .
                                    esc_html__( 'more', 'multicurrency-for-woocommerce' ) . '</a>',
                ),
            );
            return apply_filters( 'mcwc_display_rules_fields', $fields );
        }

        /**
         * Retrieve and format the list of configured currency codes.
         *
         * This function fetches the 'mcwc_settings' option, extracts the currency codes
         * from the 'currencies' array, and stores them in a simplified array.
         *
         * @return void
         */
        public static function configure_currencies() {
            $settings   = get_option( 'mcwc_settings' );
            $currencies = array();

            if ( ! empty( $settings['currencies'] ) && is_array( $settings['currencies'] ) ) :
                foreach ( $settings['currencies'] as $currency_data ) :
                    if ( isset( $currency_data['currency'] ) && ! empty( $currency_data['currency'] ) ) :
                        $currencies[] = $currency_data['currency'];
                    endif;
                endforeach;
            endif;

            return $currencies;
        }

    }

endif;