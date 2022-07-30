<?php

require_once  __DIR__ . '/functions.php';


//Source: https://wpmudev.com/blog/wordpress-admin-tables/
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class leave_List_Table extends WP_List_Table {
    /**
     * Get a list of columns.
     *
     * @return array
     */
    public function get_columns() {
        return array(
            'cb'            => '<input type="checkbox" />', // to display the checkbox.
		    'name'          => 'Name',
            'room'          => 'Room',
            'total_days'    => 'Total Days',
            'reason'        => 'Reason',
            'leaving_at'    => 'Leaving At',
            'rejoining_at'  => 'Rejoining At',
        );
    }
    public function no_items() {
        _e( 'No messages avaliable.', 'anzarianz' );
    }

    public function fetch_table_data() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'anzarianz_leaves';		
        $orderby = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : 'updated_at';
        $order = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'DESC';
        $status = $_GET['status'];
        if($status) {
            if($status == 'all' || $status == 'added') {
                $where_status = "status != 'trash' AND status != 'spam' AND status != 'approved' AND status != 'rejected'";
            } else if($status == 'spam') {
                $where_status = "status = 'spam'";
            } else if($status == 'trash') {
                $where_status = "status = 'trash'";
            } else if($status == 'approved') {
                $where_status = "status = 'approved'";
            } else if($status == 'rejected') {
                $where_status = "status = 'rejected'";
            }
        } else {
            $where_status = "status != 'trash' AND status != 'spam' AND status != 'approved' AND status != 'rejected'";
        }

        $leave_query = "SELECT 
                            *
                        FROM 
                            $table_name 
                        WHERE
                            $where_status
                        ORDER BY $orderby $order";

        // query output_type will be an associative array with ARRAY_A.
        $query_results = $wpdb->get_results( $leave_query, ARRAY_A  );
        
        // return result array to prepare_items.
        return $query_results;	
    }

    /**
     * Prepares the list of items for displaying.
     */
    public function prepare_items() {
        $columns  = $this->get_columns();
        $sortable = $this->get_sortable_columns();
        $hidden   = array();
        $this->get_bulk_actions();
        $table_data = $this->fetch_table_data();
        $total_leaves = count( $table_data );
        // $message = get_current_message_id();
        // $screen = get_current_screen();
        // $option = $screen->get_option('per_page', 'option'); 
        // $perpage = get_user_meta($message, $option, true);
        $this->_column_headers = array($columns,$hidden,$sortable);
	    
        //used by WordPress to build and fetch the _column_headers property
        $this->_column_headers = $this->get_column_info();		      
            
        // code to handle data operations like sorting and filtering
        // check if a search was performed.
        $user_search_key = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';
        
        $this->_column_headers = $this->get_column_info();
        
        // check and process any actions such as bulk actions.
        $this->handle_table_actions();

        // fetch the table data
        $table_data = $this->fetch_table_data();
        // filter the data in case of a search
        if( $user_search_key ) {
            $table_data = $this->filter_table_data( $table_data, $user_search_key );
        }	
        // rest of the code
            
        // start by assigning your data to the items variable
        $this->items = $table_data;	
            
        // code to handle pagination
        $leaves_per_page = 100;//$this->get_items_per_page( 'leaves_per_page' );
        $table_page = $this->get_pagenum();

        // provide the ordered data to the List Table
        // we need to manually slice the data based on the current pagination
        $this->items = array_slice( $table_data, ( ( $table_page - 1 ) * $leaves_per_page ), $leaves_per_page );

        // set the pagination arguments		
        $this->set_pagination_args( array (
            'total_items' => $total_leaves,
            'per_page'    => $leaves_per_page,
            'total_pages' => ceil( $total_leaves/$leaves_per_page )
        ) );
    }

    /**
     * Generates content for a single row of the table.
     * 
     * @param object $item The current item.
     * @param string $column_name The current column name.
     */
    protected function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'name':
                return esc_html( $item['user_id'] );
            case 'room':
                return esc_html( $item['user_id'] );
            case 'total_days':
                return '0';
            case 'reason':
                return esc_html( $item['reason'] );
            case 'leaving_at':
                return esc_html(date('m-d-Y h:i A', strtotime($item['leaving_at'])));
            case 'rejoining_at':
                return esc_html(date('m-d-Y h:i A', strtotime($item['rejoining_at'])));
            return 'Unknown';
        }
    }

    /**
    * Decide which columns to activate the sorting functionality on
    * @return array $sortable, the array of columns that can be sorted by the user
    */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'leaving_at'  => array('leaving_at',true),
            'name'  => array('name',true) ); 
        return $sortable_columns;
    }

    // filter the table data based on the search key
    public function filter_table_data( $table_data, $search_key ) {
        $filtered_table_data = array_values( array_filter( $table_data, function( $row ) use( $search_key ) {
            foreach( $row as $row_val ) {
                if( stripos( $row_val, $search_key ) !== false ) {
                    return true;
                }				
            }
        } ) );

        return $filtered_table_data;
    }

    protected function get_views() { 
        $status_links = array(
            "all"       => ($_GET['status'] == 'all' || !$_GET['status'])?'All':"<a href='?page=".$_GET['page']."&status=all'>All</a>",
            "approved"  => $_GET['status'] == 'approved'?'Approved':"<a href='?page=".$_GET['page']."&status=approved'>Approved</a>",
            "rejected"    => $_GET['status'] == 'rejected'?'Rejected':"<a href='?page=".$_GET['page']."&status=rejected'>Rejected</a>",
            "spam"      => $_GET['status'] == 'spam'?'Spam':"<a href='?page=".$_GET['page']."&status=spam'>Spam</a>",
            "trash"     => $_GET['status'] == 'trash'?'Trash':"<a href='?page=".$_GET['page']."&status=trash'>Trash</a>"
        );
        return $status_links;
    }

    /**
     * [OPTIONAL] this is example, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */

    function column_name($item) {
        // create a nonce
        $action_nonce = wp_create_nonce( 'action_nonce' );
        $user_info = get_userdata($item['user_id']);
        $user_name = $user_info->display_name;
        $title = sprintf('<strong><a class="row-title" href="?page=%s&action=view&leave_id=%s" aria-label="%s">%s</a></strong>', $_GET['page'], $item['id'], $user_name, $user_name);

        $actions = array(
            'approved'   => sprintf('<a href="?page=%s&action=approved&leave_id=%s&status=%s&_wpnonce=%s">Approve</a>', $_GET['page'], $item['id'], $item['status'], $action_nonce),
            'restore'   => sprintf('<a href="?page=%s&action=restore&leave_id=%s&status=%s&_wpnonce=%s">Restore</a>', $_GET['page'], $item['id'], $item['status'], $action_nonce),
            'trash'     => sprintf('<a href="?page=%s&action=trash&leave_id=%s&status=%s&_wpnonce=%s">Trash</a>', $_GET['page'], $item['id'], $item['status'], $action_nonce),
            'spam'      => sprintf('<a href="?page=%s&action=spam&leave_id=%s&status=%s&_wpnonce=%s">Spam</a>', $_GET['page'], $item['id'], $item['status'], $action_nonce),
        );

        $status = $_GET['status'];
        if($status) {
            if($status == 'all') {
                unset($actions["restore"]);
            } else if($status == 'spam') {
                unset($actions["spam"]);
            } else if($status == 'trash') {
                unset($actions["trash"]);
                $actions['delete'] = sprintf('<a href="?page=%s&action=delete&leave_id=%s&status=%s&_wpnonce=%s">Delete Permanently</a>', $_GET['page'], $item['id'], $item['status'], $action_nonce);
            } else if($status == 'approved') {
                unset($actions["approved"]);
                $actions["restore"] = sprintf('<a href="?page=%s&action=restore&leave_id=%s&status=%s&_wpnonce=%s" role="button">Unapprove</a>', $_GET['page'], $item['id'], $item['status'], $action_nonce);
            } else if($status == 'rejected') {
                unset($actions["rejected"]);
            }
        } else {
            unset($actions["restore"]);
        }

        return sprintf('%1$s %2$s', $title, $this->row_actions($actions) );
    
    }
    
    protected function column_total_days( $item ) {
        $leaving_at = $item['leaving_at'];
        $rejoining_at = $item['rejoining_at'];
        
        return get_user_meta($item['user_id'], 'room_no', true);
    }

    protected function column_room( $item ) {
        return get_user_meta($item['user_id'], 'room_no', true);
    }

    /**
     * Get value for checkbox column.
     *
     * @param object $item  A row's data.
     * @return string Text to be placed inside the column <td>.
     */
    protected function column_cb( $item ) {
        return sprintf('<input type="checkbox" name="leave_ids[]" value="%s" />', $item['id']);
    }

    /**
    * Returns an associative array containing the bulk action
    *
    * @return array
    */
    public function get_bulk_actions() {
        $actions = array(
            'approved'   => 'Approve',
            'rejected'    => 'Reject',
            'restore'   => 'Restore',
            'spam'      => 'Mark as spam',
            'trash'     => 'Move to Trash'
        );

        $status = $_GET['status'];
        if($status) {
            if($status == 'all') {
                unset($actions["restore"]);
            } else if($status == 'spam') {
                unset($actions["spam"]);
            } else if($status == 'trash') {
                unset($actions["trash"]);
                $actions['delete'] = 'Delete Permanently';
            } else if($status == 'approved') {
                unset($actions["approved"]);
            } else if($status == 'rejected') {
                unset($actions["rejected"]);
            }
        } else {
            unset($actions["restore"]);
        }

        return $actions;
    }

    public function handle_table_actions() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'anzarianz_leaves';
        $action = isset($_GET['action'])? trim($_GET['action']) : "";
        $rejection_note = isset($_GET['rejection_note'])? trim($_GET['rejection_note']) : "Admin rejected your request, reason not specified";
        $leave_id = isset($_GET['leave_id'])? intval($_GET['leave_id']) : "";
        $nonce = esc_attr( $_REQUEST['_wpnonce'] );

        // If the single action is triggered
        if(($action === 'trash' || $action === 'spam' || $action === 'restore' || $action === 'approved' || $action === 'delete' || $action === 'rejected') && $leave_id) {
            if ( ! wp_verify_nonce( $nonce, 'action_nonce' ) ) {
                die( 'Go get a life script kiddies' );
            } else {
                if ($action === 'trash') {
                    $wpdb->query("update $table_name set status='trash', rejection_note='Admin trashed your request' WHERE id = $leave_id");
                } else if ($action === 'spam') {
                    $wpdb->query("update $table_name set status='spam', rejection_note='Your request has been marked as spam' WHERE id = $leave_id");
                    send_leave_status_notification($leave_id, 'Warning', 'Your leave request has been marked as spam');
                } else if ($action === 'restore') {
                    $wpdb->query("update $table_name set status='added', rejection_note='Processing' WHERE id = $leave_id");
                } else if ($action === 'approved') {
                    $wpdb->query("update $table_name set status='approved', rejection_note='Approved' WHERE id = $leave_id");
                    send_leave_status_notification($leave_id, 'Approved', 'Your leave request approved');
                } else if ($action === 'rejected') {
                    $wpdb->query("update $table_name set status='rejected', rejection_note='$rejection_note' WHERE id = $leave_id");
                    send_leave_status_notification($leave_id, 'Rejected', 'Your leave request rejected, Reason: '.$rejection_note);
                } else if ($action === 'delete') {
                    $wpdb->query("DELETE FROM $table_name WHERE id = $leave_id");
                }
            }
        }

        // If the bulk action is triggered
        if ( ( isset( $_GET['action'] ) && $_GET['action'] == 'trash' )
            && ( isset( $_GET['action2'] ) && $_GET['action2'] == 'trash' )) {
            $leave_ids = esc_sql( $_GET['leave_ids'] );

            foreach ( $leave_ids as $id ) {
                $wpdb->query("update $table_name set status='trash', rejection_note='Admin trashed your request' WHERE id = $id");
            }
        } else if ( ( isset( $_GET['action'] ) && $_GET['action'] == 'spam' )
            && ( isset( $_GET['action2'] ) && $_GET['action2'] == 'spam' )) {
            $leave_ids = esc_sql( $_GET['leave_ids'] );

            foreach ( $leave_ids as $id ) {
                $wpdb->query("update $table_name set status='spam', rejection_note='Your request has been marked as spam' WHERE id = $id");
                send_leave_status_notification($leave_id, 'Warning', 'Your leave request has been marked as spam');
            }
        } else if ( ( isset( $_GET['action'] ) && $_GET['action'] == 'restore' )
            && ( isset( $_GET['action2'] ) && $_GET['action2'] == 'restore' )) {
            $leave_ids = esc_sql( $_GET['leave_ids'] );

            foreach ( $leave_ids as $id ) {
                $wpdb->query("update $table_name set status='added', rejection_note='Processing' WHERE id = $id");
            }
        } else if ( ( isset( $_GET['action'] ) && $_GET['action'] == 'approved' )
            && ( isset( $_GET['action2'] ) && $_GET['action2'] == 'approved' )) {
            $leave_ids = esc_sql( $_GET['leave_ids'] );

            foreach ( $leave_ids as $id ) {
                $wpdb->query("update $table_name set status='approved', rejection_note='Approved' WHERE id = $id");
                send_leave_status_notification($leave_id, 'Approved', 'Your leave request approved');
            }
        } else if ( ( isset( $_GET['action'] ) && $_GET['action'] == 'rejected' )
            && ( isset( $_GET['action2'] ) && $_GET['action2'] == 'rejected' )) {
            $leave_ids = esc_sql( $_GET['leave_ids'] );

            foreach ( $leave_ids as $id ) {
                $wpdb->query("update $table_name set status='rejected', rejection_note='$rejection_note' WHERE id = $id");
                send_leave_status_notification($leave_id, 'Rejected', 'Your leave request rejected, Reason: '.$rejection_note);
            }
        } else if ( ( isset( $_GET['action'] ) && $_GET['action'] == 'delete' )
            && ( isset( $_GET['action2'] ) && $_GET['action2'] == 'delete' )) {
            $leave_ids = esc_sql( $_GET['leave_ids'] );

            foreach ( $leave_ids as $id ) {
                $wpdb->query("DELETE FROM $table_name WHERE id = $id");
            }
        }
    }
}