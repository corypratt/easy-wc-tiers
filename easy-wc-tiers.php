<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://lonemill.com
 * @since             1.0.0
 * @package           Easy_Wc_Tiers
 *
 * @wordpress-plugin
 * Plugin Name:       Easy WC Tiers
 * Plugin URI:        https://lonemill.com
 * Description:       Simple pricing tiers for WooCommerce
 * Version:           1.0.0
 * Author:            Cory Pratt
 * Author URI:        https://lonemill.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy-wc-tiers
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EASY_WC_TIERS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-easy-wc-tiers-activator.php
 */
function activate_easy_wc_tiers() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-wc-tiers-activator.php';
	Easy_Wc_Tiers_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-easy-wc-tiers-deactivator.php
 */
function deactivate_easy_wc_tiers() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-wc-tiers-deactivator.php';
	Easy_Wc_Tiers_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_easy_wc_tiers' );
register_deactivation_hook( __FILE__, 'deactivate_easy_wc_tiers' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-easy-wc-tiers.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_easy_wc_tiers() {

	$plugin = new Easy_Wc_Tiers();
	$plugin->run();

}
run_easy_wc_tiers();
