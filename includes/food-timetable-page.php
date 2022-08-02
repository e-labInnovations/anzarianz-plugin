<?php

function food_timetable_html() {
    $action = isset($_GET['action'])? trim($_GET['action']) : "";
    $food_id = isset($_GET['food_id'])? intval($_GET['food_id']) : "";

    ob_start();
    include_once __DIR__ . '/food-timetable-table.php';
    $template = ob_get_contents();
    ob_end_clean();
    echo $template;
    
    $food_timetable_table = new food_List_Table();
    $food_timetable_table->items = $contacts;
    $food_timetable_table->prepare_items();
    ?>
    <div class="wrap">    
        <h2>Food List</h2>
        <div id="nds-wp-list-table-demo">			
            <div id="nds-post-body">
                <?php $food_timetable_table->views(); ?>	
                <form id="nds-user-list-form" method="get">
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                    <?php 
                        $food_timetable_table->search_box('Find', 'nds-user-find');
                        $food_timetable_table->display(); 
                    ?>					
                </form>
            </div>			
        </div>
    </div>
    <?php
}