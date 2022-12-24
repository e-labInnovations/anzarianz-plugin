<?php

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
        // $leave_table->items = $contacts;
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