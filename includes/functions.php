<?php



/**
 * Send notification to user
 *
 * @param integer $leave_id ID of the leave
 * @param string $title Title of the notification
 * @param string $body Body of the notifications
 * @return array|WP_Error|null The response or WP_Error on failure.
 */
function send_leave_status_notification($leave_id, $title, $body) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'anzarianz_leaves';
    $leave_query = "SELECT * FROM $table_name WHERE id = $leave_id";
    $leave_data = $wpdb->get_results($leave_query)[0];
    $push_token = get_the_author_meta('push_token', $leave_data->user_id);
    if($push_token && $leave_data) {
        $message = array(
            "to"    => $push_token,
            "title" => $title,
            "body"  => $body,
            "data"  => $leave_data,
        );
        $response = wp_remote_post('https://exp.host/--/api/v2/push/send', array(
            'body'    => wp_json_encode($message),
            'headers' => array(
                'Accept-encoding' => 'gzip, deflate',
                'Content-Type' => 'application/json',
            ),
        ));
        return $response;
    }
    return null;
}
