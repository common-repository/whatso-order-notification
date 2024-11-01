<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.whatso.net/
 * @since             1.0.0
 * @package           Whatsapp_order_notification
 *
 * @wordpress-plugin
 * Plugin Name:       Order Notification by Whatso
 * Plugin URI:        https://wordpress.org/plugins/WhatsApp-Order-Notification/
 * Description:       Instantly owner and customer gets order notifications.
 * Version:           1.1
 * Author:            whatso
 * Author URI:        https://www.whatso.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       whatso
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
define( 'WHATSAPP_ORDER_NOTIFICATION_VERSION', '1.1' );
define( 'WHATSAPP_ORDER_NOTIFICATION_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-whatsapp_order_notification-activator.php
 */
function activate_whatsapp_order_notification() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-whatsapp_order_notification-activator.php';
	Whatsapp_order_notification_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-whatsapp_order_notification-deactivator.php
 */
function deactivate_whatsapp_order_notification() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-whatsapp_order_notification-deactivator.php';
	Whatsapp_order_notification_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_whatsapp_order_notification' );
register_deactivation_hook( __FILE__, 'deactivate_whatsapp_order_notification' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-whatsapp_order_notification.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_whatsapp_order_notification() {

	$plugin = new Whatsapp_order_notification();
	$plugin->run();

}
run_whatsapp_order_notification();
