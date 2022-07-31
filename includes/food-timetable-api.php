<?php

add_action('rest_api_init', 'anzarianz_register_food_timetable');
function anzarianz_register_food_timetable() {
    register_rest_route('anzarianz/v1', 'food-timetable', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'anzarianz_get_food_timetable',
        'permission_callback'   => function () {
            return current_user_can('administrator') || current_user_can('student') || current_user_can('warden') || current_user_can('super_student') || current_user_can('worker');
        }
    ));
}

//Get the food timetable
function anzarianz_get_food_timetable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'anzarianz_food_timetable';

    $query = "SELECT * FROM $table_name";
    $query_results = $wpdb->get_results($query);
    return $query_results;
}