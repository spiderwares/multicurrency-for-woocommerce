<?php
/**
 *  Select Field Template
 */

 // Prevent direct access to the file.
defined( 'ABSPATH' ) || exit; ?>

<td>
    <div class="mcwc-select-field">
        <select id="<?php echo esc_attr( $field_Key ); ?>" 
                name="<?php echo isset( $field['name'] ) ? esc_attr( $field['name'] ) : ''; ?>"
                data-hide="<?php echo isset( $field['data_hide'] ) ? esc_attr( $field['data_hide'] ) : '';  ?>">
            <?php if ( isset( $field['options'] ) && is_array( $field['options'] ) ) : ?>
                <?php $disabled_options = isset( $field['disabled_options'] ) ? $field['disabled_options'] : array(); ?>
                <?php foreach ( $field['options'] as $key => $label ) : ?>
                    <option value="<?php echo esc_attr( $key ); ?>" 
                        <?php selected( $field_Val, $key ); ?>
                        data-show=".<?php echo esc_attr( $key ); ?>"
                        <?php echo in_array( $key, $disabled_options ) ? 'disabled' : ''; ?>>
                        <?php echo esc_html( $label ); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <small><?php echo isset( $field['desc'] ) ? wp_kses_post( $field['desc'] ) : ''; ?></small>
</td>
