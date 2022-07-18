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

    //Warden role
    add_role(
        'warden',
        'Warden',
        array(
            'read'         => true,
            'delete_posts' => false
        )
    );

    //Super Student role
    add_role(
        'super_student',
        'Super Student',
        array(
            'read'         => true,
            'delete_posts' => false
        )
    );
 
}
add_action('admin_init', 'anzarianz_new_role');