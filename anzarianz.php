<?php
/**
 * Plugin Name: Anzarianz
 * Plugin URI: http://www.elabins.com/anzarianz-plugin
 * Description: Plugin for Anzarianz project
 * Version: 1.0
 * Author: Mohammed Ashad MM
 * Author URI: http://www.elabins.com
 * Text Domain: anzarianz
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Helper constants.
define( 'ANZARIANZ_PLUGIN_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'ANZARIANZ_PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );


//User Custom Fileds
require_once  __DIR__ . '/includes/user-fields.php';
//User api
require_once  __DIR__ . '/includes/user-api.php';
//User roles
require_once  __DIR__ . '/includes/user-role.php';
//leaves table
require_once  __DIR__ . '/includes/leaves-db-table.php';