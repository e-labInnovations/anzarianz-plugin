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

    $query = "SELECT * FROM $table_name WHERE status != 'trash'";
    $query_results = $wpdb->get_results($query);
    function format_data($item) {
        $time = $item->time;
        $food_timetable_data = get_option('anzarianz_food_timetable_timings',array(
            'breakfast' => array('start' => '08:00', 'end' => '09:00'),
            'lunch'     => array('start' => '12:00', 'end' => '14:00'),
            'tea'       => array('start' => '15:00', 'end' => '17:00'),
            'dinner'    => array('start' => '19:00', 'end' => '20:00')
        ));
        $item->time = $food_timetable_data[$time];
        return $item;
    }
    $result = array_map('format_data', $query_results);
    return $result;
}