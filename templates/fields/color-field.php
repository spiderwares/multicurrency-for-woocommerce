<?php
/**
 * Color Field Template
 */

// Prevent direct access to the file.
defined( 'ABSPATH' ) || exit; ?>

<td class="forminp forminp-color">
    <span class="colorpickpreview" style="background: <?php echo esc_attr( $field_Val ); ?>;"></span>
    <input name="<?php echo isset( $field['name'] ) ? esc_attr( $field['name'] ) : ''; ?>" 
           id="<?php echo esc_attr( $field_Key ); ?>" 
           type="text" 
           class="mcwc-colorpicker" 
           value="<?php echo esc_attr( $field_Val ); ?>" 
           placeholder="<?php echo isset( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : ''; ?>" />
    <p class="description"><?php echo isset( $field['desc'] ) ? wp_kses_post( $field['desc'] ) : ''; ?></p>
</td>
