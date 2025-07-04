<?php

function mcwc_currency_options( $selected = '' ) {
    $currencies = array(
            'AED'   => 'AED-United Arab Emirates dirham (د.إ)',
            'AFN'   => 'AFN-Afghan afghani (؋)',
            'ALL'   => 'ALL-Albanian lek (L)',
            'AMD'   => 'AMD-Armenian dram (AMD)',
            'ANG'   => 'ANG-Netherlands Antillean guilder (ƒ)',
            'AOA'   => 'AOA-Angolan kwanza (Kz)',
            'ARS'   => 'ARS-Argentine peso ($)',
            'AUD'   => 'AUD-Australian dollar ($)',
            'AWG'   => 'AWG-Aruban florin (Afl.)',
            'AZN'   => 'AZN-Azerbaijani manat (₼)',
            'BAM'   => 'BAM-Bosnia and Herzegovina convertible mark (KM)',
            'BBD'   => 'BBD-Barbadian dollar ($)',
            'BDT'   => 'BDT-Bangladeshi taka (৳&nbsp;)',
            'BGN'   => 'BGN-Bulgarian lev (лв.)',
            'BHD'   => 'BHD-Bahraini dinar (.د.ب)',
            'BIF'   => 'BIF-Burundian franc (Fr)',
            'BMD'   => 'BMD-Bermudian dollar ($)',
            'BND'   => 'BND-Brunei dollar ($)',
            'BOB'   => 'BOB-Bolivian boliviano (Bs.)',
            'BRL'   => 'BRL-Brazilian real (R$)',
            'BSD'   => 'BSD-Bahamian dollar ($)',
            'BTC'   => 'BTC-Bitcoin (฿)',
            'BTN'   => 'BTN-Bhutanese ngultrum (Nu.)',
            'BWP'   => 'BWP-Botswana pula (P)',
            'BYR'   => 'BYR-Belarusian ruble (old) (Br)',
            'BYN'   => 'BYN-Belarusian ruble (Br)',
            'BZD'   => 'BZD-Belize dollar ($)',
            'CAD'   => 'CAD-Canadian dollar ($)',
            'CDF'   => 'CDF-Congolese franc (Fr)',
            'CHF'   => 'CHF-Swiss franc (CHF)',
            'CLP'   => 'CLP-Chilean peso ($)',
            'CNY'   => 'CNY-Chinese yuan (¥)',
            'COP'   => 'COP-Colombian peso ($)',
            'CRC'   => 'CRC-Costa Rican colón (₡)',
            'CUC'   => 'CUC-Cuban convertible peso ($)',
            'CUP'   => 'CUP-Cuban peso ($)',
            'CVE'   => 'CVE-Cape Verdean escudo ($)',
            'CZK'   => 'CZK-Czech koruna (Kč)',
            'DJF'   => 'DJF-Djiboutian franc (Fr)',
            'DKK'   => 'DKK-Danish krone (kr.)',
            'DOP'   => 'DOP-Dominican peso (RD$)',
            'DZD'   => 'DZD-Algerian dinar (د.ج)',
            'EGP'   => 'EGP-Egyptian pound (EGP)',
            'ERN'   => 'ERN-Eritrean nakfa (Nfk)',
            'ETB'   => 'ETB-Ethiopian birr (Br)',
            'EUR'   => 'EUR-Euro (€)',
            'FJD'   => 'FJD-Fijian dollar ($)',
            'FKP'   => 'FKP-Falkland Islands pound (£)',
            'GBP'   => 'GBP-Pound sterling (£)',
            'GEL'   => 'GEL-Georgian lari (₾)',
            'GGP'   => 'GGP-Guernsey pound (£)',
            'GHS'   => 'GHS-Ghana cedi (₵)',
            'GIP'   => 'GIP-Gibraltar pound (£)',
            'GMD'   => 'GMD-Gambian dalasi (D)',
            'GNF'   => 'GNF-Guinean franc (Fr)',
            'GTQ'   => 'GTQ-Guatemalan quetzal (Q)',
            'GYD'   => 'GYD-Guyanese dollar ($)',
            'HKD'   => 'HKD-Hong Kong dollar ($)',
            'HNL'   => 'HNL-Honduran lempira (L)',
            'HRK'   => 'HRK-Croatian kuna (kn)',
            'HTG'   => 'HTG-Haitian gourde (G)',
            'HUF'   => 'HUF-Hungarian forint (Ft)',
            'IDR'   => 'IDR-Indonesian rupiah (Rp)',
            'ILS'   => 'ILS-Israeli new shekel (₪)',
            'IMP'   => 'IMP-Manx pound (£)',
            'INR'   => 'INR-Indian rupee (₹)',
            'IQD'   => 'IQD-Iraqi dinar (د.ع)',
            'IRR'   => 'IRR-Iranian rial (﷼)',
            'IRT'   => 'IRT-Iranian toman (تومان)',
            'ISK'   => 'ISK-Icelandic króna (kr.)',
            'JEP'   => 'JEP-Jersey pound (£)',
            'JMD'   => 'JMD-Jamaican dollar ($)',
            'JOD'   => 'JOD-Jordanian dinar (د.ا)',
            'JPY'   => 'JPY-Japanese yen (¥)',
            'KES'   => 'KES-Kenyan shilling (KSh)',
            'KGS'   => 'KGS-Kyrgyzstani som (сом)',
            'KHR'   => 'KHR-Cambodian riel (៛)',
            'KMF'   => 'KMF-Comorian franc (Fr)',
            'KPW'   => 'KPW-North Korean won (₩)',
            'KRW'   => 'KRW-South Korean won (₩)',
            'KWD'   => 'KWD-Kuwaiti dinar (د.ك)',
            'KYD'   => 'KYD-Cayman Islands dollar ($)',
            'KZT'   => 'KZT-Kazakhstani tenge (₸)',
            'LAK'   => 'LAK-Lao kip (₭)',
            'LBP'   => 'LBP-Lebanese pound (ل.ل)',
            'LKR'   => 'LKR-Sri Lankan rupee (රු)',
            'LRD'   => 'LRD-Liberian dollar ($)',
            'LSL'   => 'LSL-Lesotho loti (L)',
            'LYD'   => 'LYD-Libyan dinar (د.ل)',
            'MAD'   => 'MAD-Moroccan dirham (د.م.)',
            'MDL'   => 'MDL-Moldovan leu (MDL)',
            'MGA'   => 'MGA-Malagasy ariary (Ar)',
            'MKD'   => 'MKD-Macedonian denar (ден)',
            'MMK'   => 'MMK-Burmese kyat (Ks)',
            'MNT'   => 'MNT-Mongolian tögrög (₮)',
            'MOP'   => 'MOP-Macanese pataca (P)',
            'MRU'   => 'MRU-Mauritanian ouguiya (UM)',
            'MUR'   => 'MUR-Mauritian rupee (₨)',
            'MVR'   => 'MVR-Maldivian rufiyaa (.ރ)',
            'MWK'   => 'MWK-Malawian kwacha (MK)',
            'MXN'   => 'MXN-Mexican peso ($)',
            'MYR'   => 'MYR-Malaysian ringgit (RM)',
            'MZN'   => 'MZN-Mozambican metical (MT)',
            'NAD'   => 'NAD-Namibian dollar (N$)',
            'NGN'   => 'NGN-Nigerian naira (₦)',
            'NIO'   => 'NIO-Nicaraguan córdoba (C$)',
            'NOK'   => 'NOK-Norwegian krone (kr)',
            'NPR'   => 'NPR-Nepalese rupee (₨)',
            'NZD'   => 'NZD-New Zealand dollar ($)',
            'OMR'   => 'OMR-Omani rial (ر.ع.)',
            'PAB'   => 'PAB-Panamanian balboa (B/.)',
            'PEN'   => 'PEN-Sol (S/)',
            'PGK'   => 'PGK-Papua New Guinean kina (K)',
            'PHP'   => 'PHP-Philippine peso (₱)',
            'PKR'   => 'PKR-Pakistani rupee (₨)',
            'PLN'   => 'PLN-Polish złoty (zł)',
            'PRB'   => 'PRB-Transnistrian ruble (р.)',
            'PYG'   => 'PYG-Paraguayan guaraní (₲)',
            'QAR'   => 'QAR-Qatari riyal (ر.ق)',
            'RON'   => 'RON-Romanian leu (lei)',
            'RSD'   => 'RSD-Serbian dinar (рсд)',
            'RUB'   => 'RUB-Russian ruble (₽)',
            'RWF'   => 'RWF-Rwandan franc (Fr)',
            'SAR'   => 'SAR-Saudi riyal (ر.س)',
            'SBD'   => 'SBD-Solomon Islands dollar ($)',
            'SCR'   => 'SCR-Seychellois rupee (₨)',
            'SDG'   => 'SDG-Sudanese pound (ج.س.)',
            'SEK'   => 'SEK-Swedish krona (kr)',
            'SGD'   => 'SGD-Singapore dollar ($)',
            'SHP'   => 'SHP-Saint Helena pound (£)',
            'SLL'   => 'SLL-Sierra Leonean leone (Le)',
            'SOS'   => 'SOS-Somali shilling (Sh)',
            'SRD'   => 'SRD-Surinamese dollar ($)',
            'SSP'   => 'SSP-South Sudanese pound (£)',
            'STN'   => 'STN-São Tomé and Príncipe dobra (Db)',
            'SYP'   => 'SYP-Syrian pound (ل.س)',
            'SZL'   => 'SZL-Swazi lilangeni (E)',
            'THB'   => 'THB-Thai baht (฿)',
            'TJS'   => 'TJS-Tajikistani somoni (ЅМ)',
            'TMT'   => 'TMT-Turkmenistan manat (m)',
            'TND'   => 'TND-Tunisian dinar (د.ت)',
            'TOP'   => 'TOP-Tongan paʻanga (T$)',
            'TRY'   => 'TRY-Turkish lira (₺)',
            'TTD'   => 'TTD-Trinidad and Tobago dollar ($)',
            'TWD'   => 'TWD-New Taiwan dollar (NT$)',
            'TZS'   => 'TZS-Tanzanian shilling (Sh)',
            'UAH'   => 'UAH-Ukrainian hryvnia (₴)',
            'UGX'   => 'UGX-Ugandan shilling (UGX)',
            'USD'   => 'USD-United States (US) dollar ($)',
            'UYU'   => 'UYU-Uruguayan peso ($)',
            'UZS'   => 'UZS-Uzbekistani som (UZS)',
            'VEF'   => 'VEF-Venezuelan bolívar (2008–2018) (Bs F)',
            'VES'   => 'VES-Venezuelan bolívar (Bs.)',
            'VND'   => 'VND-Vietnamese đồng (₫)',
            'VUV'   => 'VUV-Vanuatu vatu (Vt)',
            'WST'   => 'WST-Samoan tālā (T)',
            'XAF'   => 'XAF-Central African CFA franc (CFA)',
            'XCD'   => 'XCD-East Caribbean dollar ($)',
            'XOF'   => 'XOF-West African CFA franc (CFA)',
            'XPF'   => 'XPF-CFP franc (XPF)',
            'YER'   => 'YER-Yemeni rial (﷼)',
            'ZAR'   => 'ZAR-South African rand (R)',
            'ZMW'   => 'ZMW-Zambian kwacha (ZK)',
            'LTC'   => 'LTC-Litecoin (LTC)',
            'ETH'   => 'ETH-Ethereum (ETH)',
            'ZWL'   => 'ZWL-Zimbabwe (ZWL)',
            );

    $output = '';
    foreach ( $currencies as $code => $label ) :
        $output .= sprintf(
            '<option value="%s"%s>%s</option>',
            esc_attr( $code ),
            selected( $code, $selected, false ),
            esc_html( $label )
        );
    endforeach;

    return $output;
}

/**
 * Retrieves the currently selected currency from session or cookie.
 *
 * This function checks plugin settings to determine whether to use session or cookie
 * for currency persistence and returns the selected currency code.
 *
 * @return string|null Selected currency code (e.g., 'USD', 'INR') or null if not set.
 */
function mcwc_get_selected_currency() {
    $settings = get_option( 'mcwc_settings', [] );

    if ( isset( $settings['use_session'] ) && $settings['use_session'] === 'yes' ) {
        return isset( $_SESSION['mcwc_selected_currency'] ) ? sanitize_text_field( wp_unslash( $_SESSION['mcwc_selected_currency'] ) ): null;
    }
    return $set = isset( $_COOKIE['mcwc_selected_currency'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['mcwc_selected_currency'] ) ) : get_woocommerce_currency();
}


/**
 * Sets the selected currency for the session or cookie.
 *
 * This function is used to override the current currency based on user selection
 * or programmatic triggers such as payment method selection.
 * It supports both session-based and cookie-based persistence.
 *
 * @param string $currency The currency code to switch to (e.g., 'USD', 'EUR').
 * @return void
 */
function mcwc_switch_currency( $currency ) {
    if ( ! $currency ) :
        return;
    endif;

    $settings = get_option( 'mcwc_settings', [] );

    if ( isset( $settings['use_session'] ) && $settings['use_session'] === 'yes' ) :
        if ( ! session_id() ) :
            session_start();
        endif;
        $_SESSION['mcwc_selected_currency'] = $currency;
    else :
        setcookie( 'mcwc_selected_currency', $currency, time() + (30 * DAY_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN );
    endif;
}