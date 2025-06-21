<?php
/**
 * Settings Tab: General
 * Loads the General settings section in the plugin settings page.
 * 
 * @package MultiCurrencyForWooCommerce
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Retrieve the general settings fields from the MCWC_Admin_Settings class.
 * @var array $fields Array of general settings fields.
 * 
 */
$fields = MCWC_Admin_Settings::general_field();

/**
 * Fetch the saved settings from the WordPress options table.
 * @var array|false $options Retrieved settings or false if not set.
 * 
 */
$options = get_option( 'mcwc_settings', true );

/**
 * Load the settings form template for the General settings tab.
 */
wc_get_template(
	'fields/setting-forms.php',
	array(
		'title'   => 'General',         // Section title.
		'metaKey' => 'mcwc_settings',   // Option meta key.
		'fields'  => $fields,           // Field definitions.
		'options' => $options,          // Saved option values.
	),
	'multicurrency-for-woocommerce/fields/',   // Relative template path in the plugin.
	MCWC_TEMPLATE_PATH                  // Absolute path to the template directory.
);
