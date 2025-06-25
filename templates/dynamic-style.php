<?php
/**
 * Dynamic inline styles for MultiCurrency for WooCommerce.
 *
 * @package MultiCurrencyForWooCommerce
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( empty( $settings ) || ! is_array( $settings ) ) :
	return;
endif;

$sb_text    = isset( $settings[ 'text_color' ]) ? $settings[ 'text_color' ] : '#ffffff';
$sb_hover   = isset( $settings[ 'hover_color' ]) ? $settings[ 'hover_color' ] : '#f78080';
$sb_bg      = isset( $settings[ 'background_color' ]) ? $settings[ 'background_color' ] : '#000000'; 
$custom_css = isset( $settings[ 'custom_css' ] ) ? $settings[ 'custom_css' ] : ''; ?>

.mcwc-sidebar .mcwc-title {
    background: <?php echo esc_html( $sb_bg ); ?>;
    color: <?php echo esc_html( $sb_text ); ?>;
}

.mcwc-sidebar .mcwc-list-currencies .mcwc-currency {
    background: <?php echo esc_html( $sb_bg ); ?>;
    color: <?php echo esc_html( $sb_text ); ?>;
}

.mcwc-sidebar .mcwc-list-currencies .mcwc-currency.mcwc-active,
.mcwc-sidebar .mcwc-list-currencies .mcwc-currency:hover {
    background: <?php echo esc_html( $sb_hover ); ?>;
}

<?php if ( ! empty( $custom_css ) ) : ?>
<?php echo wp_strip_all_tags( $custom_css ); ?>
<?php endif; ?>