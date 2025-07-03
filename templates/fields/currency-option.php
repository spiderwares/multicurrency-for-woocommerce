<?php
/**
 * Repeater Field Template
 */

// Prevent direct access to the file.
defined( 'ABSPATH' ) || exit;
?>

<td colspan="2">
    <table class="mcwc-repeater-table">
        <thead>
            <tr>
                <th class="mcwc-center"><?php esc_html_e( 'Default', 'multicurrency-for-woocommerce' ); ?></th>
                <th><?php esc_html_e( 'Hidden', 'multicurrency-for-woocommerce' ); ?></th>
                <th><?php esc_html_e( 'Currency', 'multicurrency-for-woocommerce' ); ?></th>
                <th><?php esc_html_e( 'Position', 'multicurrency-for-woocommerce' ); ?></th>
                <th><?php esc_html_e( 'Rate', 'multicurrency-for-woocommerce' ); ?></th>
                <th><?php esc_html_e( 'Fee', 'multicurrency-for-woocommerce' ); ?></th>
                <th><?php esc_html_e( 'Decimals', 'multicurrency-for-woocommerce' ); ?></th>
                <th><?php esc_html_e( 'Custom Symbol', 'multicurrency-for-woocommerce' ); ?></th>
                <th class="mcwc-center"><?php esc_html_e( 'Action', 'multicurrency-for-woocommerce' ); ?></th>
            </tr>
        </thead>
        <tbody class="mcwc-repeater-body">
            <tr class="mcwc-repeater-template" style="display: none;">
                <td class="mcwc-center"><input class="mcwc-default-currency" type="radio" name="<?php echo esc_attr( $field['name'] ); ?>[default][]" value="AED"></td>
                <td>
                    <select class="mcwc-full-width" name="<?php echo esc_attr( $field['name'] ); ?>[hidden][]">
                        <option value="no"><?php esc_html_e( 'No', 'multicurrency-for-woocommerce' ); ?></option>
                        <option value="yes"><?php esc_html_e( 'Yes', 'multicurrency-for-woocommerce' ); ?></option>
                    </select>
                </td>
                <td>
					<select name="<?php echo esc_attr( $field['name'] ); ?>[currency][]" class="mcwc-currency-select mcwc-full-width">
                        <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo mcwc_currency_options(); ?>
					</select>
				</td>
                <td>
                    <select class="mcwc-full-width" name="<?php echo esc_attr( $field['name'] ); ?>[position][]">
                        <option value="left"><?php esc_html_e( 'Left $99', 'multicurrency-for-woocommerce' ); ?></option>
                        <option value="right"><?php esc_html_e( 'Right 99$', 'multicurrency-for-woocommerce' ); ?></option>
                        <option value="left_space"><?php esc_html_e( 'Left $ 99', 'multicurrency-for-woocommerce' ); ?></option>
                        <option value="right_space"><?php esc_html_e( 'Right 99 $', 'multicurrency-for-woocommerce' ); ?></option>
                    </select>
                </td>
                <td><input type="text" name="<?php echo esc_attr( $field['name'] ); ?>[rate][]" value=""></td>
                <td><input type="number" name="<?php echo esc_attr( $field['name'] ); ?>[fee][]" value=""></td>
                <td><input type="number" name="<?php echo esc_attr( $field['name'] ); ?>[decimals][]" value="2"></td>
                <td><input type="text" name="<?php echo esc_attr( $field['name'] ); ?>[symbol][]" value=""></td>
                <td class="mcwc-center">
                    <button class="mcwc-remove-repeater-row" title="<?php esc_attr_e( 'Remove row', 'multicurrency-for-woocommerce' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="28" height="28" x="0" y="0" viewBox="0 0 64 64" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M49 14h-9v-1c0-2.757-2.243-5-5-5h-6c-2.757 0-5 2.243-5 5v1h-9a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h34a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2zm-21-1c0-.551.449-1 1-1h6c.551 0 1 .449 1 1v1h-8zM48.28 23.655A2.001 2.001 0 0 0 46.8 23H17.2a2.001 2.001 0 0 0-1.991 2.191l2.458 25.688c.279 2.92 2.848 5.122 5.976 5.122h16.714c3.127 0 5.697-2.202 5.976-5.122l2.458-25.688a2 2 0 0 0-.511-1.536zM27.326 50.996c-.043.002-.085.004-.128.004a2 2 0 0 1-1.994-1.874l-1.2-19a2.001 2.001 0 0 1 1.87-2.122 2.005 2.005 0 0 1 2.122 1.87l1.2 19a2.001 2.001 0 0 1-1.87 2.122zm11.47-1.87a2 2 0 1 1-3.992-.252l1.2-19a2 2 0 0 1 3.992.252z" fill="#f44336" opacity="1" data-original="#000000" class=""></path></g></svg></button>
                </td>
            </tr>

            <?php if ( ! empty( $field_Val ) && is_array( $field_Val ) ) : ?>
                <?php foreach ( $field_Val as $index => $item ) : ?>
                    <tr>
                        <td class="mcwc-center"><input class="mcwc-default-currency" type="radio" name="<?php echo esc_attr( $field['name'] ); ?>[default][]" value="<?php echo esc_attr( $item['currency'] ); ?>" <?php checked( $item['default'], $item['currency'] ); ?>></td>
                        <td>
                            <select class="mcwc-full-width" name="<?php echo esc_attr( $field['name'] ); ?>[hidden][]">
                                <option value="no" <?php selected( $item['hidden'], 'no' ); ?>><?php esc_html_e( 'No', 'multicurrency-for-woocommerce' ); ?></option>
                                <option value="yes" <?php selected( $item['hidden'], 'yes' ); ?>><?php esc_html_e( 'Yes', 'multicurrency-for-woocommerce' ); ?></option>
                            </select>
                        </td>
                        <td>
                            <select name="<?php echo esc_attr( $field['name'] ); ?>[currency][]" class="mcwc-currency-select mcwc-full-width">
                                <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                <?php echo mcwc_currency_options( $item['currency'] ); ?>
                            </select>
                        </td>
                        <td>
                            <select class="mcwc-full-width" name="<?php echo esc_attr( $field['name'] ); ?>[position][]">
                                <option value="left" <?php selected( $item['position'], 'left' ); ?>><?php esc_html_e( 'Left $99', 'multicurrency-for-woocommerce' ); ?></option>
                                <option value="right" <?php selected( $item['position'], 'right' ); ?>><?php esc_html_e( 'Right 99$', 'multicurrency-for-woocommerce' ); ?></option>
                                <option value="left_space" <?php selected( $item['position'], 'left_space' ); ?>><?php esc_html_e( 'Left $ 99', 'multicurrency-for-woocommerce' ); ?></option>
                                <option value="right_space" <?php selected( $item['position'], 'right_space' ); ?>><?php esc_html_e( 'Right 99 $', 'multicurrency-for-woocommerce' ); ?></option>
                            </select>
                        </td>
                        <td><input type="text" name="<?php echo esc_attr( $field['name'] ); ?>[rate][]" value="<?php echo esc_attr( $item['rate'] ); ?>"></td>
                        <td><input type="number" name="<?php echo esc_attr( $field['name'] ); ?>[fee][]" value="<?php echo esc_attr( $item['fee'] ); ?>"></td>
                        <td><input type="number" name="<?php echo esc_attr( $field['name'] ); ?>[decimals][]" value="<?php echo esc_attr( $item['decimals'] ); ?>"></td>
                        <td><input type="text" name="<?php echo esc_attr( $field['name'] ); ?>[symbol][]" value="<?php echo esc_attr( $item['symbol'] ); ?>"></td>
                        <td class="mcwc-center">
                            <button class="mcwc-remove-repeater-row">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="28" height="28" x="0" y="0" viewBox="0 0 64 64" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M49 14h-9v-1c0-2.757-2.243-5-5-5h-6c-2.757 0-5 2.243-5 5v1h-9a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h34a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2zm-21-1c0-.551.449-1 1-1h6c.551 0 1 .449 1 1v1h-8zM48.28 23.655A2.001 2.001 0 0 0 46.8 23H17.2a2.001 2.001 0 0 0-1.991 2.191l2.458 25.688c.279 2.92 2.848 5.122 5.976 5.122h16.714c3.127 0 5.697-2.202 5.976-5.122l2.458-25.688a2 2 0 0 0-.511-1.536zM27.326 50.996c-.043.002-.085.004-.128.004a2 2 0 0 1-1.994-1.874l-1.2-19a2.001 2.001 0 0 1 1.87-2.122 2.005 2.005 0 0 1 2.122 1.87l1.2 19a2.001 2.001 0 0 1-1.87 2.122zm11.47-1.87a2 2 0 1 1-3.992-.252l1.2-19a2 2 0 0 1 3.992.252z" fill="#f44336" opacity="1" data-original="#000000" class=""></path></g></svg>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" class="mcwc_currency_options_actions" style="text-align: end;">
                    <button class="mcwc-update-all-rates mcwc-admin-button">
                        <span><?php esc_html_e( 'Update rates', 'multicurrency-for-woocommerce' ); ?></span>
                    </button>
                    
                    <img src="<?php echo esc_url( admin_url( 'images/spinner.gif' ) ); ?>" class="mcwc-spinner" />

                    <button class="mcwc-add-repeater-row mcwc-admin-button">
                        <span><?php esc_html_e( 'Add Currency', 'multicurrency-for-woocommerce' ); ?></span>
                    </button>

                    <div id="mcwc-update-rate-msg" class="mcwc-error"></div>
                </td>
            </tr>
        </tfoot>
    </table>
</td>