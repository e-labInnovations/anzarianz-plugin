<?php

// Act on plugin activation
// register_activation_hook( __FILE__, "anzarianz_create_leave_db" );
add_action('activate_anzarianz/anzarianz.php', 'anzarianz_create_mess_guest_db');
function anzarianz_create_mess_guest_db() {
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'anzarianz_mess_guest';

	$sql = "CREATE TABLE $table_name (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        user_id bigint(20) unsigned NOT NULL,
        added_by bigint(20) unsigned NOT NULL,
		date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		food_id bigint(20) unsigned NOT NULL,
        note varchar(60) NOT NULL DEFAULT '',
        status varchar(20) NOT NULL DEFAULT 'added',
		UNIQUE KEY id (id)
	) $charset_collate;";

	dbDelta( $sql );
}

//Test
// add_action('admin_head', 'onAdminRefresh03');
function onAdminRefresh03() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'anzarianz_mess_guest';
    $wpdb->insert($table_name, array(
        'user_id' => 7,
        'added_by' => 1,
        'food_id' => 8
    ));
}