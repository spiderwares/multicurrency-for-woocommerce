<?php 
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<div class="wrap">
    <h2 class="mcwc-response-message"></h2>
</div>
<div class="jthemes_page jthemes_settings_page wrap">
    <div class="card">
        <!-- Display the plugin title and version -->
        <h2 class="mcwc-title">
            <?php 
            // Output the plugin title, version, and premium label (if applicable).
            echo esc_html__( 'Multi Currency for WooCommerce', 'multicurrency-for-woocommerce' ) . ' ' . esc_html( MCWC_VERSION ) . ' ' . 
            ( defined( 'MCWC_PREMIUM' ) 
                ? '<span class="premium" style="display: none">' . esc_html__( 'Premium', 'multicurrency-for-woocommerce' ) . '</span>' 
                : '' 
            ); 
            ?>
        </h2>

        <!-- Plugin description and external links -->
        <div class="jthemes_settings_page_desc about-text">
            <p>
                <?php 
                // translators: %s is a span with 5 red stars (★) for visual star rating.
                printf(  esc_html__( 'Thank you for choosing our plugin! If you’re happy with its performance, we’d be grateful if you could give us a five-star %s rating. Your support helps us improve and deliver even better features.', 'multicurrency-for-woocommerce' ), 
                    '<span style="color:#ff0000">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' );  ?>
                <br/>
                <!-- Add links to reviews, changelog, and discussion pages -->
                <a href="<?php echo esc_url( MCWC_REVIEWS ); ?>" target="_blank"><?php esc_html_e( 'Reviews', 'multicurrency-for-woocommerce' ); ?></a> |
                <a href="<?php echo esc_url( MCWC_CHANGELOG ); ?>" target="_blank"><?php esc_html_e( 'Changelog', 'multicurrency-for-woocommerce' ); ?></a> |
                <a href="<?php echo esc_url( MCWC_DISCUSSION ); ?>" target="_blank"><?php esc_html_e( 'Discussion', 'multicurrency-for-woocommerce' ); ?></a>
            </p>
        </div>
    </div>

    <!-- Navigation tabs for plugin settings -->
    <div class="jthemes_settings_page_nav">
        <h2 class="nav-tab-wrapper">
            <!-- General settings tab -->
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=jthemes-mcwc&tab=general' ) ); ?>" 
               class="<?php echo esc_attr( $active_tab === 'general' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
                <img src="<?php echo esc_url( MCWC_URL . 'assets/img/admin/general.svg'); ?>" />
                <?php esc_html_e( 'General', 'multicurrency-for-woocommerce' ); ?>
            </a>

            <!-- Location settings tab -->
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=jthemes-mcwc&tab=location' ) ); ?>" 
               class="<?php echo esc_attr( $active_tab === 'location' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
                <img src="<?php echo esc_url( MCWC_URL . 'assets/img/admin/location.svg'); ?>" />
                <?php esc_html_e( 'Location', 'multicurrency-for-woocommerce' ); ?>
            </a>

            <!-- Checkout settings tab -->
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=jthemes-mcwc&tab=checkout' ) ); ?>" 
               class="<?php echo esc_attr( $active_tab === 'checkout' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
                <img src="<?php echo esc_url( MCWC_URL . 'assets/img/admin/checkout.svg'); ?>" />
                <?php esc_html_e( 'Checkout', 'multicurrency-for-woocommerce' ); ?>
            </a>

            <!-- Design settings tab -->
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=jthemes-mcwc&tab=design' ) ); ?>" 
               class="<?php echo esc_attr( $active_tab === 'design' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
                <img src="<?php echo esc_url( MCWC_URL . 'assets/img/admin/design.svg'); ?>" />
                <?php esc_html_e( 'Design', 'multicurrency-for-woocommerce' ); ?>
            </a>
            <?php /*
            <!-- Price Foramte settings tab -->
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=jthemes-mcwc&tab=price-format' ) ); ?>" 
               class="<?php echo esc_attr( $active_tab === 'price-format' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
                <img src="<?php echo esc_url( MCWC_URL . 'assets/img/admin/price-format.svg'); ?>" />
                <?php esc_html_e( 'Price Foramte', 'multicurrency-for-woocommerce' ); ?>
            </a>  */ ?>

            <a href="<?php echo esc_url( admin_url( 'admin.php?page=jthemes-mcwc&tab=visibility' ) ); ?>" 
               class="<?php echo esc_attr( $active_tab === 'visibility' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
                <img src="<?php echo esc_url( MCWC_URL . 'assets/img/admin/visibility.svg'); ?>" />
                <?php esc_html_e( 'Visibility', 'multicurrency-for-woocommerce' ); ?>
            </a>
            
            <!-- Update settings tab -->
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=jthemes-mcwc&tab=update' ) ); ?>" 
               class="<?php echo esc_attr( $active_tab === 'update' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
                <img src="<?php echo esc_url( MCWC_URL . 'assets/img/admin/update.svg'); ?>" />
                <?php esc_html_e( 'Update', 'multicurrency-for-woocommerce' ); ?>
            </a>

            <!-- Premium version tab, visible only if not in the premium version -->
            <?php /* if ( ! defined( 'MCWC_PREMIUM' ) ) : ?>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=jthemes-mcwc&tab=premium' ) ); ?>" 
                   class="<?php echo esc_attr( $active_tab === 'premium' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>" 
                   style="color: #c9356e;">
                    <img src="<?php echo esc_url( MCWC_URL . 'assets/img/admin/general.svg'); ?>" />
                    <?php esc_html_e( 'Premium Version', 'multicurrency-for-woocommerce' ); ?>
                </a>
            <?php endif; */ ?>
        </h2>
    </div>

    <!-- Content area for the active settings tab -->
    <div class="jthemes_settings_page_content">
        <?php
        // Load the content for the currently active tab dynamically.
        require_once MCWC_PATH . 'includes/admin/settings/views/' . $active_tab . '.php';
        ?>
    </div>
</div>
