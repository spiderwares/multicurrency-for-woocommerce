<?php
if ( ! defined( 'ABSPATH' ) ) :
    exit; 
endif; ?>

<?php foreach ( $fields as $field_Key => $field ) : 
    $field_Val  = isset( $options[ $field_Key ] ) ? $options[ $field_Key ] : $field['default']; 
    $field_type = isset( $field[ 'field_type' ] ) ? $field[ 'field_type' ] : ''; ?>

    <tr class="<?php echo isset( $field['extra_class'] ) ? esc_attr( $field['extra_class'] ) : '';  ?>"

        <?php if( isset($field[ 'style' ] ) && !empty( $field[ 'style' ] ) ): 
            $style = explode('.', $field['style'], 2); ?>
            style="<?php echo esc_attr( ( isset( $options[ $style[0] ] ) && $options[ $style[0] ] == $style[1] ) ? '' : 'display: none;' ); ?>" 
        <?php endif; ?>
         >
        <?php if( $field['field_type'] != 'mcwccurrencyoption' && $field['field_type'] != 'mcwc_currency_by_countries' ):  ?>
            <th scope="row" class="mcwc-label <?php echo esc_attr( $field_type ); ?>" <?php echo ( $field_type === 'mcwctitle' ) ? 'colspan="2"' : ''; ?>>
                <?php echo esc_html( $field['title'] ); ?>
            </th>
        <?php endif; ?>
        
        <?php
        switch ( $field['field_type'] ) {

            case "mcwctext" : 
                wc_get_template(
                    'fields/text-field.php',
                    array(
                        'field'         => $field,
                        'field_Val'     => $field_Val,
                        'field_Key'     => $field_Key
                    ),
                    'multicurrency-for-woocommerce/',
                    MCWC_TEMPLATE_PATH
                );
                break;

            case "mcwcselect":
                wc_get_template(
                    'fields/select-field.php', 
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'multicurrency-for-woocommerce/',
                    MCWC_TEMPLATE_PATH
                );
                break;

            case "mcwcswitch":
                wc_get_template(
                    'fields/switch-field.php', 
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'multicurrency-for-woocommerce/',
                    MCWC_TEMPLATE_PATH
                );
                break;

            case "mcwcbuypro":
                wc_get_template(
                    'fields/buy-pro-field.php',
                    array(
                        'field' => $field
                    ),
                    'multicurrency-for-woocommerce/',
                    MCWC_TEMPLATE_PATH
                );
                break;
                
            case "mcwccolor":
                wc_get_template(
                    'fields/color-field.php', 
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'multicurrency-for-woocommerce/',
                    MCWC_TEMPLATE_PATH
                );
                break;

            case "mcwcnumber":
                wc_get_template(
                    'fields/number-field.php',
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'multicurrency-for-woocommerce/',
                    MCWC_TEMPLATE_PATH
                );
                break;

            case "mcwctextarea":
                wc_get_template(
                    'fields/textarea-field.php',
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'multicurrency-for-woocommerce/',
                    MCWC_TEMPLATE_PATH
                );
                break;

            case "mcwccurrencyoption":
                wc_get_template(
                    'fields/currency-option.php',
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'multicurrency-for-woocommerce/',
                    MCWC_TEMPLATE_PATH
                );
                break;
            
            case "mcwc_currency_by_countries":
                wc_get_template(
                    'fields/currency-by-countries.php',
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'multicurrency-for-woocommerce/',
                    MCWC_TEMPLATE_PATH
                );
                break;
            
            case "mcwc_currency_by_payment_method":
                wc_get_template(
                    'fields/currency-by-payment-method.php',
                    array(
                        'field'     => $field,
                        'field_Val' => $field_Val,
                        'field_Key' => $field_Key,
                    ),
                    'multicurrency-for-woocommerce/',
                    MCWC_PRO_TEMPLATE_PATH
                );
                break;
            
            case "mcwc_checkout_currency":
                wc_get_template(
                    'fields/checkout-currency.php',
                    array(
                        'field'                 => $field,
                        'field_Val'             => $field_Val,
                        'field_Key'             => $field_Key,
                        'field_name'            => $field['name'],
                        'currencies'            => isset( $field['currencies'] ) ? $field['currencies'] : [],
                        'payment_options'       => $field['payment_options'],
                        'checkout_currency'     => isset( $field_Val['checkout_currency'] ) ? $field_Val['checkout_currency'] : '',
                        'checkout_currency_args'=> isset( $field_Val['checkout_currency_args'] ) ? $field_Val['checkout_currency_args'] : [],
                    ),
                    'multicurrency-for-woocommerce/',
                    MCWC_PRO_TEMPLATE_PATH
                );
                break;

        }
        ?>
    </tr>

<?php endforeach; ?>