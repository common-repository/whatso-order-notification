<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://www.whatso.net/
 * @since      1.0.0
 *
 * @package    Whatsapp_order_notification
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// remove plugin options
global $wpdb;



/*
 * @var $table_name
 * name of table to be dropped
 * prefixed with $wpdb->prefix from the database
 */
$table_name = $wpdb->prefix . 'wp_whatso_order_notification_details';

// drop the table from the database.
$wpdb->get_results( $wpdb->prepare("DROP TABLE IF EXISTS $table_name" )); //phpcs:ignore
delete_option( 'order_notification' );

