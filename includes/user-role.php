<?php

function anzarianz_new_role() {  
    //Student role
    add_role(
        'student',
        'Student',
        array(
            'read'         => true,
            'delete_posts' => false
        )
    );

    //Worker role
    add_role(
        'worker',
        'Worker',
        array(
            'read'         => true,
            'delete_posts' => false
        )
    );

    //Student role
    add_role(
        'warden',
        'Warden',
        array(
            'read'         => true,
            'delete_posts' => false
        )
    );
 
}
add_action('admin_init', 'anzarianz_new_role');