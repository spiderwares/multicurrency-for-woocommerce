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

    <?php
    $current_flag_code  = isset($map[strtoupper($selected_currency)]) ? $map[strtoupper($selected_currency)] : 'xx';
    $current_price      = '';
    foreach ($currencies as $currency) :
        if ($currency['code'] === $selected_currency) :
            $current_price = $currency['price'];
            break;
        endif;
    endforeach; ?>
    <div class="mcwc-dropdown-toggle">
        <span class="mcwc-flag flag-<?php echo esc_attr($current_flag_code); ?>"></span>
        <span class="mcwc-product-price"><?php echo wp_kses_post($current_price); ?></span>
        <span class="mcwc-dropdown-icon">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="14" height="14" x="0" y="0" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                <g>
                    <path d="M225.923 354.706c-8.098 0-16.195-3.092-22.369-9.263L9.27 151.157c-12.359-12.359-12.359-32.397 0-44.751 12.354-12.354 32.388-12.354 44.748 0l171.905 171.915 171.906-171.909c12.359-12.354 32.391-12.354 44.744 0 12.365 12.354 12.365 32.392 0 44.751L248.292 345.449c-6.177 6.172-14.274 9.257-22.369 9.257z" fill="#000000" opacity="1" data-original="#000000" class=""></path>
                </g>
            </svg>
        </span>
    </div>
    
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
