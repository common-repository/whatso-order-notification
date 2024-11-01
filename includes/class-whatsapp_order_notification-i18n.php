<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.whatso.net/
 * @since      1.0.0
 *
 * @package    Whatsapp_order_notification
 * @subpackage Whatsapp_order_notification/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Whatsapp_order_notification
 * @subpackage Whatsapp_order_notification/includes
 * @author     whatso <hi@whatso.net>
 */
class Whatsapp_order_notification_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'whatsapp_order_notification',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
