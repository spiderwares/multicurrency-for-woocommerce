<?php
/**
 *  Switch Field Template
 */

 // Prevent direct access to the file.
defined( 'ABSPATH' ) || exit; ?>

<td>
    <div class="mcwc-switch-field">
        <input type="hidden" name="<?php echo isset( $field['name'] ) ? esc_attr( $field['name'] ) : ''; ?>" value="no" />
        <input type="checkbox" 
               id="<?php echo esc_attr( $field_Key ); ?>" 
               name="<?php echo isset( $field['name'] ) ? esc_attr( $field['name'] ) : ''; ?>" 
               value="yes" <?php checked( $field_Val, 'yes' ); ?>
               data-show="<?php echo isset( $field['data_show'] ) ? esc_attr( $field['data_show'] ) : ''; ?>" />
        <label for="<?php echo esc_attr( $field_Key ); ?>">
        <span class="mcwc-switch-icon">
            <svg class="mcwc-icon-on" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" role="img">
				<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
			</svg>
            <svg class="mcwc-icon-off" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" role="img">
				<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
			</svg>
        </span>
        </label>
    </div>
    <div>
        <small><?php echo isset( $field['desc'] ) ? wp_kses_post( $field['desc'] ) : ''; ?></small>
    </div>
</td>