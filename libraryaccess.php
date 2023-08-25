<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wisdmlabs.com
 * @since             1.0.0
 * @package           Libraryaccess
 *
 * @wordpress-plugin
 * Plugin Name:       Library Access - Access All Courses
 * Plugin URI:        https://wisdmlabs.com
 * Description:       The product that has library access enabled is designed to provide users with access to all current and upcoming courses upon purchase.
 * Version:           1.0.0
 * Author:            Sumeet Dubey
 * Author URI:        https://wisdmlabs.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       libraryaccess
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
define( 'LIBRARYACCESS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-libraryaccess-activator.php
 */
function activate_libraryaccess() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-libraryaccess-activator.php';
	Libraryaccess_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-libraryaccess-deactivator.php
 */
function deactivate_libraryaccess() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-libraryaccess-deactivator.php';
	Libraryaccess_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_libraryaccess' );
register_deactivation_hook( __FILE__, 'deactivate_libraryaccess' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-libraryaccess.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_libraryaccess() {

	$plugin = new Libraryaccess();
	$plugin->run();

}
run_libraryaccess();
