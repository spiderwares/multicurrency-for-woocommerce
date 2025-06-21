<?php
/**
 * Settings Tab: Design
 * Loads the Design settings section in the plugin settings page.
 * 
 * @package MultiCurrencyForWooCommerce
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Retrieve the design settings fields from the MCWC_Admin_Settings class.
 * @var array $fields Array of design settings fields.
 */
$fields = MCWC_Admin_Settings::design_fields();

/**
 * Fetch the saved settings from the WordPress options table.
 * @var array|false $options Retrieved settings or false if not set.
 */
$options = get_option( 'mcwc_settings', true );

/**
 * Load the settings form template for the Design settings tab.
 */
wc_get_template(
	'fields/setting-forms.php',
	array(
		'title'   => 'Design',          // Section title.
		'metaKey' => 'mcwc_settings',   // Option meta key.
		'fields'  => $fields,           // Field definitions.
		'options' => $options,          // Saved option values.
	),
	'multicurrency-for-woocommerce/fields/',  // Relative template path in the plugin.
	MCWC_TEMPLATE_PATH                 // Absolute path to the template directory.
);
