<td colspan="2" style="padding:0;">
    <table class="mcwc-table mcwc-currency-by-country">
        <thead>
        <tr>
            <th><?php esc_html_e( 'Currency', 'multicurrency-for-woocommerce' ) ?></th>
            <th><?php esc_html_e( 'Countries', 'multicurrency-for-woocommerce' ) ?></th>
            <th class="mcwc-currency-by-country-action"><?php esc_html_e( 'Actions', 'multicurrency-for-woocommerce' ) ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $currencies     = isset( $field['currencies'] ) ? $field['currencies'] : array();
        $wc_countries   = WC()->countries->get_countries();

        foreach ( $currencies as $currency ) :
            $selected_countries = isset( $field_Val[ $currency ] ) ? $field_Val[ $currency ] : array(); ?>
            <tr>
                <td><?php echo esc_html( '(' . get_woocommerce_currency_symbol( $currency ) . ') ' . $currency ) ?></td>
                <td>
                    <select multiple
                            name="<?php echo esc_attr( $field['name'] ); ?>[<?php echo esc_attr( $currency ); ?>][]"
                            class="mcwc_currency_country"
                            data-placeholder="<?php esc_attr_e( 'Please select countries', 'multicurrency-for-woocommerce' ) ?>">
                        <?php foreach ( $wc_countries as $country_code => $country_name ) : ?>
                            <option value="<?php echo esc_attr( $country_code ); ?>" <?php selected( in_array( $country_code, $selected_countries ), true ); ?>>
                                <?php echo esc_html( $country_name ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <button class="mcwc-admin-button mcwc-select-all-countries">
                        <?php esc_html_e( 'Select all', 'multicurrency-for-woocommerce' ); ?>
                    </button>
                    <button class="mcwc-admin-button mcwc-remove-all-countries">
                        <?php esc_html_e( 'Remove all', 'multicurrency-for-woocommerce' ); ?>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</td>