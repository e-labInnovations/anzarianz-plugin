<?php

function anzarianz_user_api() {
    register_rest_field('user', 'room_no', array(
        'get_callback'  => 'rest_get_user_field',
        'update_callback'   => null,
        'schema'            => null,
    ));
    register_rest_field('user', 'room_type', array(
        'get_callback'  => 'rest_get_user_field',
        'update_callback'   => null,
        'schema'            => null,
    ));
}
add_action('rest_api_init', 'anzarianz_user_api');

function rest_get_user_field( $user, $field_name, $request ) {
    return get_user_meta( $user[ 'id' ], $field_name, true );
}