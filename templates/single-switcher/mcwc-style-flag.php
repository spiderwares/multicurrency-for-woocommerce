<?php
/**
 * Template for single product currency switcher - Dropdown on hover with flags only.
 *
 * @var array $currencies
 * @var string $position
 * @var string $title
 * @var array $map
 */                

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) :
    exit;
endif; ?>

<div class="mcwc-single-dropdown-wrapper mcwc-position-<?php echo esc_attr( $position ); ?>">
    <div class="mcwc-single-dropdown">
        <div class="mcwc-dropdown-list mcwc-currency-switcher">
              <?php foreach ( $currencies as $currency ) :
                $flag_code = isset( $map[ strtoupper( $currency['code'] ) ] ) ? $map[ strtoupper( $currency['code'] ) ] : 'xx'; ?>
                <?php $active = ( $currency['code'] === $selected_currency ) ? 'mcwc-active' : ''; ?>
                <div class="mcwc-dropdown-item mcwc-currency <?php echo esc_attr( $active ); ?>" data-currency="<?php echo esc_attr( $currency['code'] ); ?>">
                    <span class="mcwc-flag flag-<?php echo esc_attr( $flag_code ); ?>"></span> <?php echo $flag_code; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
