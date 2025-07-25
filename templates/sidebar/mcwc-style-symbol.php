<?php
/**
 * Template for currency switcher - Symbol Only.
 *
 * @var array $currencies
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) :
	exit; // Prevent direct access for security reasons.
endif; ?>

<div class="mcwc-<?php echo esc_attr( $position ); ?> mcwc-currency-symbol-style mcwc-bottom mcwc-sidebar">
    <div class="mcwc-list-currencies">
        <div class="mcwc-title"><?php echo esc_html( $title ); ?></div>
        <div class="mcwc-currency-switcher">
            <?php foreach ( $currencies as $currency ) : 
                $nonce      = wp_create_nonce( 'mcwc_switch_currency' );
                $perameter  = 'mcwc_currency='.$currency['code'].'&mcwc_nonce='.$nonce; 
                $active     = ( $currency['code'] === $selected_currency ) ? 'mcwc-active' : ''; ?>
                <a class="mcwc-currency <?php echo esc_attr( $active ); ?>" href="?<?php echo esc_attr( $perameter ); ?>" <?php echo esc_attr( $rel_nofollow ); ?> data-currency="<?php echo esc_attr( $currency['code'] ); ?>">
                    <span class="mcwc-currency-symbol"><?php echo esc_html( $currency['symbol'] ); ?></span>
                </a>
            <?php endforeach ?>
        </div>
        <div class="mcwc-sidebar-open"></div>
    </div>
</div>
