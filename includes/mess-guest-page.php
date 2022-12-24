<?php

function mess_guest_html() {
    $action = isset($_GET['action'])? trim($_GET['action']) : "";
    $food_id = isset($_GET['food_id'])? intval($_GET['food_id']) : "";

    ob_start();
    include_once __DIR__ . '/mess-guest-table.php';
    $template = ob_get_contents();
    ob_end_clean();
    echo $template;
    
    $mess_guest_table = new mess_guest_List_Table();
    // $mess_guest_table->items = $contacts;
    $mess_guest_table->prepare_items();
    ?>
    <div class="wrap">    
        <h1 class="wp-heading-inline">Food List</h1>
        <a href="?page=anzarianz_food_add_new" class="page-title-action">Add New</a>
        <div id="nds-wp-list-table-demo">		
            <div id="nds-post-body">
                <?php $mess_guest_table->views(); ?>	
                <form id="nds-user-list-form" method="get">
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                    <?php 
                        $mess_guest_table->search_box('Find', 'nds-user-find');
                        $mess_guest_table->display(); 
                    ?>					
                </form>
            </div>			
        </div>
    </div>
    <?php
}