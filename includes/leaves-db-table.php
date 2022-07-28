<?php

// Act on plugin activation
// register_activation_hook( __FILE__, "anzarianz_create_leave_db" );
add_action('activate_anzarianz/anzarianz.php', 'anzarianz_create_leave_db');
function anzarianz_create_leave_db() {
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'anzarianz_leaves';

	$sql = "CREATE TABLE $table_name (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        user_id bigint(20) unsigned NOT NULL,
        added_by bigint(20) unsigned NOT NULL,
        leaving_at DATETIME NOT NULL,
        rejoining_at DATETIME NOT NULL,
        reason varchar(60) NOT NULL DEFAULT '',
        rejection_note varchar(60) NOT NULL DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        status varchar(20) NOT NULL DEFAULT 'added',
		UNIQUE KEY id (id)
	) $charset_collate;";

	dbDelta( $sql );
}

//Test
// add_action('admin_head', 'onAdminRefresh01');
function onAdminRefresh01() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'anzarianz_leaves';

    // $now = new DateTime();

    // $wpdb->insert($table_name, array(
    //     'user_id' => 1,
    //     'added_by' => 1,
    //     'leaving_at' => $now->format('Y-m-d H:i:s'),
    //     'rejoining_at' => $now->format('Y-m-d H:i:s'),
    //     'reason' => 'Vacation',
    // ));

    $now = new DateTime();
    $rejoining_time = date("Y-m-d H:i:s", strtotime('2022-07-18 08:37:00'));

    $wpdb->insert($table_name, array(
        'user_id' => 1,
        'added_by' => 1,
        'leaving_at' => $now->format('Y-m-d H:i:s'),
        'rejoining_at' => $rejoining_time,
        'reason' => 'Vacation',
    ));
}