<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://jerl92.tk
 * @since             1.0.0
 * @package           School
 *
 * @wordpress-plugin
 * Plugin Name:       WPSchool
 * Plugin URI:        https://jerl92tk/me/WPSchool
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Jeremie Langevin
 * Author URI:        https://jerl92.tk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       school
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
define( 'SCHOOL_VERSION', '0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-school-activator.php
 */
function activate_school() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-school-activator.php';
	School_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-school-deactivator.php
 */
function deactivate_school() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-school-deactivator.php';
	School_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_school' );
register_deactivation_hook( __FILE__, 'deactivate_school' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-school.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_school() {

	$plugin = new School();
	$plugin->run();

}
run_school();
