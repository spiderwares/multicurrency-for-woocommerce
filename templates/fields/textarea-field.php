<?php
/**
 *  Textarea Field Template
*/
// Prevent direct access to the file.
defined( 'ABSPATH' ) || exit; ?>

<td>
    <div class="mcwc-textarea-field">
        <textarea id="<?php echo esc_attr( $field_Key ); ?>" 
                  name="<?php echo isset( $field['name'] ) ? esc_attr( $field['name'] ) : ''; ?>" 
                  placeholder="<?php echo isset( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : ''; ?>" 
                  rows="<?php echo isset( $field['rows'] ) ? esc_attr( $field['rows'] ) : '4'; ?>" 
                  cols="<?php echo isset( $field['cols'] ) ? esc_attr( $field['cols'] ) : '50'; ?>" style="width: 600px;"><?php echo esc_textarea( $field_Val ); ?></textarea>
    </div>
    <small><?php echo isset( $field['desc'] ) ? wp_kses_post( $field['desc'] ) : ''; ?></small>
</td>