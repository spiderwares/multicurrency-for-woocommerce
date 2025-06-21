<div class="jthemes_page jthemes_welcome_page wrap">
    <h1>Jthemes | Innovation meets creativity</h1>
    <div class="card">
        <h2 class="title">About Jthemes</h2>
        <p>
            I am an independent developer focused on creating innovative plugins for WordPress. My aim is to provide smart tools and features, especially for enhancing the WooCommerce platform. Visit my website to learn more: 
            <a href="https://jthemes.com/" target="_blank">https://jthemes.com</a>
        </p>
    </div>
</div>

<div class="card">
    <h2 class="title">Our WordPress.org Plugins</h2>
    <ul id="jthemes-plugins">
        <?php
        $response = wp_remote_get( 'https://jthemes.com/plugins.json' ); // Replace with your real JSON file URL
        if ( ! is_wp_error( $response ) ) {
            $plugins = json_decode( wp_remote_retrieve_body( $response ), true );

            if ( is_array( $plugins ) ) {
                foreach ( $plugins as $plugin ) {
                    $name = esc_html( $plugin['name'] );
                    $slug = esc_attr( $plugin['slug'] );
                    $installs = esc_html( $plugin['installs'] );
                    echo "<li><a href='https://wordpress.org/plugins/{$slug}/' target='_blank'>{$name}</a> – Active installs: {$installs}</li>";
                }
            } else {
                echo '<li>Unable to load plugin list. Please try again later.</li>';
            }
        } else {
            echo '<li>Failed to fetch plugin data from jthemes.com</li>';
        }
        ?>
    </ul>
</div>