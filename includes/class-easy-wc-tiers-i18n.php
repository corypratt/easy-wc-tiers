<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://lonemill.com
 * @since      1.0.0
 *
 * @package    Easy_Wc_Tiers
 * @subpackage Easy_Wc_Tiers/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Easy_Wc_Tiers
 * @subpackage Easy_Wc_Tiers/includes
 * @author     Cory Pratt <cory@lonemill.com>
 */
class Easy_Wc_Tiers_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'easy-wc-tiers',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
