<?php

function anzarianz_user_api() {
    //Register meta
    register_meta('user', 'room_no', array(
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => true,
    ));
    register_meta('user', 'room_type', array(
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => true,
    ));
    register_meta('user', 'mobile_no', array(
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => true,
    ));
    register_meta('user', 'permanent_address', array(
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => true,
    ));
    register_meta('user', 'date_of_admission', array(
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => true,
    ));
    register_meta('user', 'dob', array(
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => true,
    ));
    register_meta('user', 'food_preference', array(
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => true,
    ));
    register_meta('user', 'religion_and_cast', array(
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => true,
    ));
    register_meta('user', 'push_token', array(
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => true,
    ));
}
add_action('rest_api_init', 'anzarianz_user_api');