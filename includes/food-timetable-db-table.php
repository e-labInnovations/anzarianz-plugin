<?php

// Act on plugin activation
// register_activation_hook( __FILE__, "anzarianz_create_leave_db" );
add_action('activate_anzarianz/anzarianz.php', 'anzarianz_create_food_timetable_db');
function anzarianz_create_food_timetable_db() {
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'anzarianz_food_timetable';

	$sql = "CREATE TABLE $table_name (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        primary_food varchar(60) NOT NULL DEFAULT '',
        secondary_food varchar(60) NOT NULL DEFAULT '',
        time varchar(20) NOT NULL DEFAULT '',
        day tinyint unsigned NOT NULL,
        status varchar(20) NOT NULL DEFAULT 'added',
		UNIQUE KEY id (id)
	) $charset_collate;";

	dbDelta( $sql );
}

//Test
// add_action('admin_head', 'onAdminRefresh02');
function onAdminRefresh02() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'anzarianz_food_timetable';
    $wpdb->insert($table_name, array(
        'primary_food' => 'പൂരി',
        'secondary_food' => 'മസാല, കട്ടൻ',
        'time' => 'morning',
        'day' => 1
    ));
}