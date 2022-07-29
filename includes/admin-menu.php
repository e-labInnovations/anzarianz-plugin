<?php

//Add Admin Menu
add_action('admin_menu', 'anzarianz_leave_menu');
function anzarianz_leave_menu() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'anzarianz_leaves';
    $leaves_query = "SELECT * FROM $table_name WHERE status = 'added'";
    $query_results = $wpdb->get_results( $leaves_query, ARRAY_A  );
    $notification_count = count($query_results);

    add_menu_page(
        'Leaves',
        $notification_count ? sprintf( 'Leaves <span class="awaiting-mod">%d</span>', $notification_count ) : 'Leaves',
        'manage_options',
        'anzarianz_leave',
        'leavesHTML',
        'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpb25pY29uIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiI+CiAgICA8dGl0bGU+Q2FsZW5kYXI8L3RpdGxlPgogICAgPHBhdGggZmlsbD0iI2E3YWFhZCIgZD0iTTQ4MCAxMjhhNjQgNjQgMCAwMC02NC02NGgtMTZWNDguNDVjMC04LjYxLTYuNjItMTYtMTUuMjMtMTYuNDNBMTYgMTYgMCAwMDM2OCA0OHYxNkgxNDRWNDguNDVjMC04LjYxLTYuNjItMTYtMTUuMjMtMTYuNDNBMTYgMTYgMCAwMDExMiA0OHYxNkg5NmE2NCA2NCAwIDAwLTY0IDY0djEyYTQgNCAwIDAwNCA0aDQ0MGE0IDQgMCAwMDQtNHpNMzIgNDE2YTY0IDY0IDAgMDA2NCA2NGgzMjBhNjQgNjQgMCAwMDY0LTY0VjE3OWEzIDMgMCAwMC0zLTNIMzVhMyAzIDAgMDAtMyAzem0zNDQtMjA4YTI0IDI0IDAgMTEtMjQgMjQgMjQgMjQgMCAwMTI0LTI0em0wIDgwYTI0IDI0IDAgMTEtMjQgMjQgMjQgMjQgMCAwMTI0LTI0em0tODAtODBhMjQgMjQgMCAxMS0yNCAyNCAyNCAyNCAwIDAxMjQtMjR6bTAgODBhMjQgMjQgMCAxMS0yNCAyNCAyNCAyNCAwIDAxMjQtMjR6bTAgODBhMjQgMjQgMCAxMS0yNCAyNCAyNCAyNCAwIDAxMjQtMjR6bS04MC04MGEyNCAyNCAwIDExLTI0IDI0IDI0IDI0IDAgMDEyNC0yNHptMCA4MGEyNCAyNCAwIDExLTI0IDI0IDI0IDI0IDAgMDEyNC0yNHptLTgwLTgwYTI0IDI0IDAgMTEtMjQgMjQgMjQgMjQgMCAwMTI0LTI0em0wIDgwYTI0IDI0IDAgMTEtMjQgMjQgMjQgMjQgMCAwMTI0LTI0eiIvPgo8L3N2Zz4=',
        100
    );
}

function leavesHTML() {
    $action = isset($_GET['action'])? trim($_GET['action']) : "";
    $leave_id = isset($_GET['leave_id'])? intval($_GET['leave_id']) : "";

    if($action == 'view' && $leave_id) {

        ob_start();
        include_once __DIR__ . '/leave-view.php';
        $template = ob_get_contents();
        ob_end_clean();
        echo $template;
    } else {
        ob_start();
        include_once __DIR__ . '/leave-table.php';
        $template = ob_get_contents();
        ob_end_clean();
        echo $template;
        
        $leave_table = new leave_List_Table();
        $leave_table->items = $contacts;
        $leave_table->prepare_items();
        ?>
        <div class="wrap">    
            <h2>Leaves</h2>
            <div id="nds-wp-list-table-demo">			
                <div id="nds-post-body">
                    <?php $leave_table->views(); ?>	
                    <form id="nds-user-list-form" method="get">
                        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                        <?php 
                            $leave_table->search_box('Find', 'nds-user-find');
                            $leave_table->display(); 
                        ?>					
                    </form>
                </div>			
            </div>
        </div>
        <?php
    }
}