<?php 
// Prevent direct access to the file.
defined( 'ABSPATH' ) || exit; ?>

<form method="post" action="options.php" enctype="multipart/form-data">
    <table class="form-table">
        <tr class="heading">
            <th colspan="2">
                <?php echo esc_html( $title ); ?>
            </th>
        </tr>
        <tr>
        <?php
            wc_get_template(
                'fields/manage-fields.php',
                array(
                    'metaKey' => $metaKey,
                    'fields'  => $fields,
                    'options' => $options,
                ),
                'multicurrency-for-woocommerce/fields/',
                MCWC_TEMPLATE_PATH
            );
        ?>
        </tr>
        <tr class="submit">
            <th colspan="2">
                <?php settings_fields( $metaKey ); ?>
                <?php do_settings_sections( 'mcwc-product-compare' ); ?>
                <?php submit_button(); ?>
                <?php settings_errors( 'mcwc_settings' ); ?>
            </th>
        </tr>
    </table>
</form>