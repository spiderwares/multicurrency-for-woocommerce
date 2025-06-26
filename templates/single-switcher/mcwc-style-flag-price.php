<?php
/**
 * Template: Flag + Converted Product Price for each currency.
 *
 * @var array  $currencies
 * @var string $position
 * @var string $title
 * @var array  $map
 */

if ( ! defined( 'ABSPATH' ) ) :
    exit;
endif; ?>

<div class="mcwc-single-dropdown-wrapper">
    <div class="mcwc-single-dropdown">
        <div class="mcwc-dropdown-list mcwc-currency-switcher">
            <?php foreach ( $currencies as $currency ) :
                $nonce      = wp_create_nonce( 'mcwc_switch_currency' );
                $perameter  = 'mcwc_currency='.$currency['code'].'&mcwc_nonce='.$nonce;
                $flag_code  = isset( $map[ strtoupper( $currency['code'] ) ] ) ? $map[ strtoupper( $currency['code'] ) ] : 'xx'; 
                $active     = ( $currency['code'] === $selected_currency ) ? 'mcwc-active' : ''; ?>
                <a class="mcwc-dropdown-item mcwc-currency <?php echo esc_attr( $active ); ?>" href="?<?php echo esc_attr( $perameter ); ?>" <?php echo esc_attr( $rel_nofollow ); ?> data-currency="<?php echo esc_attr( $currency['code'] ); ?>">
                    <span class="mcwc-flag flag-<?php echo esc_attr( $flag_code ); ?>"></span>
                    <span class="mcwc-product-price"><?php echo wp_kses_post( $currency['price'] ); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
