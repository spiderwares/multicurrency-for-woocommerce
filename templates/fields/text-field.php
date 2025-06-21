<?php
/**
 *  Text Field Template
 */
// Prevent direct access to the file.
defined( 'ABSPATH' ) || exit; ?>

<td>
    <div class="mcwc-text-field">
        <input type="text" 
               id="<?php echo esc_attr( $field_Key ); ?>" 
               name="<?php echo isset( $field['name'] ) ? esc_attr( $field['name'] ) : ''; ?>" 
               value="<?php echo esc_attr( $field_Val ); ?>" 
               placeholder="<?php echo isset( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : ''; ?>" />
    </div>
    <small><?php echo isset( $field['desc'] ) ? wp_kses_post( $field['desc'] ) : ''; ?></small>
</td>