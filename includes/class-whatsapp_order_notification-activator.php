<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.whatso.net/
 * @since      1.0.0
 *
 * @package    Whatsapp_order_notification
 * @subpackage Whatsapp_order_notification/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Whatsapp_order_notification
 * @subpackage Whatsapp_order_notification/includes
 * @author     whatso <hi@whatso.net>
 */
class Whatsapp_order_notification_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
	
		//whatso order notification
		global $wpdb;
		$table_prefix = $wpdb->prefix;
		$tblname1='whatso_order_notification_details';
		$wp_order_table = $table_prefix . "$tblname1";
		$charset_collate = $wpdb->get_charset_collate();

		$db_result1 = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $wp_order_table));
		if (strtolower($db_result1) !== strtolower($wp_order_table)) {

			$tbl1 = "CREATE TABLE $wp_order_table (
			`id`              		BIGINT(20) NOT NULL auto_increment,
			`user_type`       		VARCHAR(50) NULL DEFAULT NULL,
			`create_date_time`  	DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`message_api_request`	LONGTEXT NULL DEFAULT NULL,
			`message_api_response`  LONGTEXT NULL DEFAULT NULL,
			PRIMARY KEY (`id`)
			)$charset_collate;";
			require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
			dbDelta($tbl1);
		}

	}

}
