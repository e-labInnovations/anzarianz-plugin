<?php

require_once  __DIR__ . '/functions.php';


//Source: https://wpmudev.com/blog/wordpress-admin-tables/
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class food_List_Table extends WP_List_Table {
    /**
     * Get a list of columns.
     *
     * @return array
     */
    public function get_columns() {
        return array(
            'cb'            => '<input type="checkbox" />', // to display the checkbox.
		    'primary_food'  => 'Main Food',
            'secondary_food'=> 'Secondary Food',
            'time'          => 'Time',
            'day'           => 'Day',
        );
    }
    public function no_items() {
        _e( 'No foods avaliable.', 'anzarianz' );
    }

    public function fetch_table_data() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'anzarianz_food_timetable';		
        $orderby = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : null;
        $order = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'DESC';
        $status = isset($_GET['status'])?$_GET['status']:'all';
        if($status) {
            if($status == 'all' || $status == 'published') {
                $where_status = "status != 'trash'";
            } else if($status == 'trash') {
                $where_status = "status = 'trash'";
            }
        } else {
            $where_status = "status != 'trash'";
        }

        $order_by_text = $orderby?"ORDER BY $orderby $order":"";

        $leave_query = "SELECT 
                            *
                        FROM 
                            $table_name 
                        WHERE
                            $where_status
                        $order_by_text";

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
            case 'primary_food':
                return esc_html($item['primary_food']);
            case 'secondary_food':
                return esc_html($item['secondary_food']);
            case 'time':
                return esc_html(ucfirst($item['time']));
            case 'day':
                $food_day = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                return esc_html($food_day[$item['day']-1]);
            return 'Unknown';
        }
    }

    /**
    * Decide which columns to activate the sorting functionality on
    * @return array $sortable, the array of columns that can be sorted by the user
    */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'primary_food'  => array('primary_food',true),
            'day'  => array('day',true) ); 
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
        $status = isset($_GET['status'])?$_GET['status']:'published';
        $status_links = array(
            "all"       => $status == 'published'?'All':"<a href='?page=".$_GET['page']."&status=all'>All</a>",
            "trash"     => $status == 'trash'?'Trash':"<a href='?page=".$_GET['page']."&status=trash'>Trash</a>"
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

    function column_primary_food($item) {
        // create a nonce
        $action_nonce = wp_create_nonce( 'action_nonce' );
        $title = sprintf('<strong><a class="row-title" href="?page=anzarianz_food_add_new&food_id=%s" aria-label="%s">%s</a></strong>', $item['id'], $item['primary_food'], $item['primary_food']);

        $actions = array(
            'restore'   => sprintf('<a href="?page=%s&action=restore&food_id=%s&status=%s&_wpnonce=%s">Restore</a>', $_GET['page'], $item['id'], $item['status'], $action_nonce),
            'trash'     => sprintf('<a href="?page=%s&action=trash&food_id=%s&status=%s&_wpnonce=%s">Trash</a>', $_GET['page'], $item['id'], $item['status'], $action_nonce),
        );

        $status = isset($_GET['status'])?$_GET['status']:'all';
        if($status) {
            if($status == 'all') {
                unset($actions["restore"]);
            } else if($status == 'trash') {
                unset($actions["trash"]);
                $actions['delete'] = sprintf('<a href="?page=%s&action=delete&food_id=%s&status=%s&_wpnonce=%s">Delete Permanently</a>', $_GET['page'], $item['id'], $item['status'], $action_nonce);
            }
        } else {
            unset($actions["restore"]);
        }

        return sprintf('%1$s %2$s', $title, $this->row_actions($actions) );
    
    }

    /**
     * Get value for checkbox column.
     *
     * @param object $item  A row's data.
     * @return string Text to be placed inside the column <td>.
     */
    protected function column_cb( $item ) {
        return sprintf('<input type="checkbox" name="food_ids[]" value="%s" />', $item['id']);
    }

    /**
    * Returns an associative array containing the bulk action
    *
    * @return array
    */
    public function get_bulk_actions() {
        $actions = array(
            'restore'   => 'Restore',
            'trash'     => 'Move to Trash'
        );

        $status = isset($_GET['status'])?$_GET['status']:'all';
        if($status) {
            if($status == 'all') {
                unset($actions["restore"]);
            } else if($status == 'trash') {
                unset($actions["trash"]);
                $actions['delete'] = 'Delete Permanently';
            }
        } else {
            unset($actions["restore"]);
        }

        return $actions;
    }

    public function handle_table_actions() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'anzarianz_food_timetable';
        $action = isset($_GET['action'])? trim($_GET['action']) : "";
        $food_id = isset($_GET['food_id'])? intval($_GET['food_id']) : "";
        $nonce = isset($_REQUEST['_wpnonce'])?esc_attr($_REQUEST['_wpnonce']):null;

        // If the single action is triggered
        if(($action === 'trash' || $action === 'restore' || $action === 'delete') && $food_id) {
            if ( ! wp_verify_nonce( $nonce, 'action_nonce' ) ) {
                die( 'Go get a life script kiddies' );
            } else {
                if ($action === 'trash') {
                    $wpdb->query("UPDATE $table_name set status='trash' WHERE id = $food_id");
                } else if ($action === 'restore') {
                    $wpdb->query("UPDATE $table_name set status='published' WHERE id = $food_id");
                } else if ($action === 'delete') {
                    $wpdb->query("DELETE FROM $table_name WHERE id = $food_id");
                }
            }
        }

        // If the bulk action is triggered
        if ( ( isset( $_GET['action'] ) && $_GET['action'] == 'trash' )
            && ( isset( $_GET['action2'] ) && $_GET['action2'] == 'trash' )) {
            $food_ids = esc_sql( $_GET['food_ids'] );

            foreach ( $food_ids as $id ) {
                $wpdb->query("UPDATE $table_name set status='trash' WHERE id = $id");
            }
        } else if ( ( isset( $_GET['action'] ) && $_GET['action'] == 'restore' )
            && ( isset( $_GET['action2'] ) && $_GET['action2'] == 'restore' )) {
            $food_ids = esc_sql( $_GET['food_ids'] );

            foreach ( $food_ids as $id ) {
                $wpdb->query("UPDATE $table_name set status='published' WHERE id = $id");
            }
        } else if ( ( isset( $_GET['action'] ) && $_GET['action'] == 'delete' )
            && ( isset( $_GET['action2'] ) && $_GET['action2'] == 'delete' )) {
            $food_ids = esc_sql( $_GET['food_ids'] );

            foreach ( $food_ids as $id ) {
                $wpdb->query("DELETE FROM $table_name WHERE id = $id");
            }
        }
    }
}