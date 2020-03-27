<?php function school_admin_menu() {
    add_menu_page(    
        __( 'School Dashboard', 'school' ),    
        __( 'School', 'school' ),
        'manage_options',    
        'school-dashbaord',    
        'school_dashboard_contents',    
        'dashicons-schedule',    
        55    
    );
    add_submenu_page(
        'school-dashbaord',
        __( 'School Dashboard', 'school' ),
        __( 'Dashboard', 'school' ),
        'manage_options',    
        'school-dashbaord',    
        'school_dashboard_contents'
    ); 
    add_submenu_page(
        'school-dashbaord',
        __( 'All Classes', 'school' ),
        __( 'Classes', 'school' ),
        'edit_posts',
        'edit.php?post_type=classes'
    );   
    add_submenu_page(
        'school-dashbaord',
        __( 'All groups', 'school' ),
        __( 'Groups', 'school' ),
        'edit_posts',
        'edit.php?post_type=groups'
    );
    add_submenu_page(
        'school-dashbaord',
        __( 'All schedules', 'school' ),
        __( 'Schedules', 'school' ),
        'edit_posts',
        'edit.php?post_type=schedules'
    );
    add_submenu_page(
        'school-dashbaord',
        __( 'All Grades', 'school' ),
        __( 'Grades', 'school' ),
        'edit_posts',
        'edit-tags.php?taxonomy=grade'
    );  
    add_submenu_page(
        'school-dashbaord',
        __( 'All Category', 'school' ),
        __( 'Category', 'school' ),
        'edit_posts',
        'edit-tags.php?taxonomy=classescat'
    );  
}
add_action( 'admin_menu', 'school_admin_menu' ); ?>