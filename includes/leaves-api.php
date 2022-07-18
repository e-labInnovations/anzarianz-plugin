<?php

add_action('rest_api_init', 'anzarianz_register_leaves');
function anzarianz_register_leaves() {
    //List of leaves
    //admin/warden/super_student can get others leaves also by providing user_id
    register_rest_route('anzarianz/v1', 'leaves', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'anzarianz_get_leaves',
        'permission_callback'   => function () {
            return current_user_can('administrator') || current_user_can('student') || current_user_can('warden') || current_user_can('super_student');
        }
    ));

    //Create leaves
    register_rest_route('anzarianz/v1', 'leaves', array(
        'methods' => 'POST',
        'callback' => 'anzarianz_add_leaves',
        'permission_callback'   => function () {
            return current_user_can('administrator') || current_user_can('student') || current_user_can('warden') || current_user_can('super_student');
        }
    ));

    //Get single leaves
    register_rest_route('anzarianz/v1', 'leaves/(?P<id>\d+)', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'anzarianz_get_single_leaves',
        'permission_callback'   => function () {
            return current_user_can('administrator') || current_user_can('student') || current_user_can('warden') || current_user_can('super_student');
        }
    ));

    //Update single leaves
    register_rest_route('anzarianz/v1', 'leaves/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'anzarianz_update_single_leaves',
        'permission_callback'   => function () {
            return current_user_can('administrator') || current_user_can('student') || current_user_can('warden') || current_user_can('super_student');
        }
    ));

    //Delete single leaves
    register_rest_route('anzarianz/v1', 'leaves/(?P<id>\d+)/delete', array(
        'methods' => 'POST',
        'callback' => 'anzarianz_delete_single_leaves',
        'permission_callback'   => function () {
            return current_user_can('administrator') || current_user_can('student') || current_user_can('warden') || current_user_can('super_student');
        }
    ));
}

function anzarianz_get_leaves($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'anzarianz_leaves';
    $user = wp_get_current_user();
    $user_id = intval($data['user_id']);

    $error = new WP_Error();
    if(!(in_array('administrator', $user->roles) || in_array('warden', $user->roles) || in_array('super_student', $user->roles))) {
        if(empty($user_id)) {
            $user_id = $user->ID;
        } else if($user_id != $user->ID) {
            $error->add(
                'rest_forbidden',
                'Sorry, you are not allowed to do that!!!.',
                array('status' => rest_authorization_required_code())
            );
        }
    }

    // Send the error
    if (!empty($error->get_error_codes())) {
        return $error;
    }

    $query_where = $user_id?"WHERE user_id = $user_id" : '';

    $query = "SELECT * FROM $table_name $query_where ORDER BY added_by DESC";
    $query_results = $wpdb->get_results($query);
    return $query_results;
}

function anzarianz_add_leaves($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'anzarianz_leaves';
    $user = wp_get_current_user();

    $user_id        = intval($data['user_id']) ? intval($data['user_id']) : $user->ID;
    $added_by       = $user->ID;
    $leaving_at     = $data['leaving_at'];
    $rejoining_at   = $data['rejoining_at'];
    $reason         = sanitize_text_field($data['reason']);

    $error = new WP_Error();
    if(!(in_array('administrator', $user->roles) || in_array('warden', $user->roles) || in_array('super_student', $user->roles))) {
        if($user_id != $added_by) {
            $error->add(
                'rest_forbidden',
                'Sorry, you are not allowed to do that!!!.',
                array('status' => rest_authorization_required_code())
            );
        }
    }

    if(empty($leaving_at)) {
        $error->add(
            'empty',
            'Leaving date and time is required',
            array('status' => 400));
    }

    if(empty($rejoining_at)) {
        $error->add(
            'empty',
            'Rejoining date and time is required',
            array('status' => 400));
    }

    if(empty($reason)) {
        $error->add(
            'empty',
            'Reason is required',
            array('status' => 400));
    }

    // Send the error
    if (!empty($error->get_error_codes())) {
        return $error;
    }

    $leaving_time = date("Y-m-d H:i:s", strtotime($leaving_at));
    $rejoining_time = date("Y-m-d H:i:s", strtotime($rejoining_at));

    $wpdb->insert($table_name, array(
        'user_id' => $user_id,
        'added_by' => $added_by,
        'leaving_at' => $leaving_time,
        'rejoining_at' => $rejoining_time,
        'reason' => $reason,
    ));
  
    $query = "SELECT * FROM $table_name WHERE id = $wpdb->insert_id";
    $query_results = $wpdb->get_results($query);
    return $query_results[0];
}

function anzarianz_get_single_leaves($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'anzarianz_leaves';
    $leave_id = intval($data['id']);
    $user = wp_get_current_user();

    $query = "SELECT * FROM $table_name WHERE id = $leave_id";
    $leave = $wpdb->get_results($query)[0];
    
    $error = new WP_Error();
    if(!$leave) {
        $error->add(
            'rest_forbidden',
            "item with id $leave_id not found",
            array('status' => 404)
        );
    }

    if(!(in_array('administrator', $user->roles) || in_array('warden', $user->roles) || in_array('super_student', $user->roles))) {
        if($leave->user_id != $user->ID) {
            $error->add(
                'rest_forbidden',
                'Sorry, you are not allowed to do that!!!.',
                array('status' => rest_authorization_required_code())
            );
        }
    }
    
    // Send the error
    if (!empty($error->get_error_codes())) {
        return $error;
    }

    return $leave;
}

function anzarianz_update_single_leaves($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'anzarianz_leaves';
    $leave_id = intval($data['id']);
    $user = wp_get_current_user();

    $added_by       = $user->ID;
    $leaving_at     = $data['leaving_at'];
    $rejoining_at   = $data['rejoining_at'];
    $reason         = sanitize_text_field($data['reason']);

    $query = "UPDATE $table_name SET added_by = $added_by";

    if($leaving_at) {
        $leaving_time = date("Y-m-d H:i:s", strtotime($leaving_at));
        $query = $query . ", leaving_at = '" . $leaving_time . "'";
    }
    if($rejoining_at) {
        $rejoining_time = date("Y-m-d H:i:s", strtotime($rejoining_at));
        $query = $query . ", rejoining_at = '" . $leaving_time . "'";
    }
    if($reason) {
        $query = $query . ", reason = '" . $reason . "'";
    }
    $query = $query . " WHERE id = $leave_id";
    $wpdb->query($query);

    $query = "SELECT * FROM $table_name WHERE id = $leave_id";
    $leave = $wpdb->get_results($query)[0];
    return $leave;
}

function anzarianz_delete_single_leaves($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'anzarianz_leaves';
    $leave_id = intval($data['id']);
    $user = wp_get_current_user();

    $query = "SELECT * FROM $table_name WHERE id = $leave_id";
    $leave = $wpdb->get_results($query)[0];
    
    $error = new WP_Error();
    if(!$leave) {
        $error->add(
            'rest_forbidden',
            "item with id $leave_id not found",
            array('status' => 404)
        );
    }

    if(!(in_array('administrator', $user->roles) || in_array('warden', $user->roles) || in_array('super_student', $user->roles))) {
        if($leave->user_id != $user->ID) {
            $error->add(
                'rest_forbidden',
                'Sorry, you are not allowed to do that!!!.',
                array('status' => rest_authorization_required_code())
            );
        }
    }
    
    // Send the error
    if (!empty($error->get_error_codes())) {
        return $error;
    }

    $query = "DELETE FROM $table_name WHERE id = $leave_id";
    $wpdb->query($query);

    return array("success" => true);
}

//Whitelisting endpoints for JWT
add_filter( 'jwt_auth_whitelist', function ( $endpoints ) {
    $your_endpoints = array(
        '/wp-json/anzarianz/v1/leaves',
    );

    return array_unique( array_merge( $endpoints, $your_endpoints ) );
} );