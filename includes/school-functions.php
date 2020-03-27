<?php

/* Parent Menu Fix */
add_filter( 'submenu_file', 'my_cpt_submenu_file' );

/**
 * Fix Sub Menu Item Highlights
 */
function my_cpt_submenu_file( $submenu_file ){

    /* Get current screen */
    global $current_screen, $self;

    if ( in_array( $current_screen->taxonomy, array( 'grade' ) ) && 'classes' == $current_screen->post_type ) {
        $submenu_file = 'edit-tags.php?taxonomy=grade&post_type=classes';
    }

    if ( in_array( $current_screen->taxonomy, array( 'classescat' ) ) && 'classes' == $current_screen->post_type ) {
        $submenu_file = 'edit-tags.php?taxonomy=classescat&post_type=classes';
    }

    if ( in_array( $current_screen->taxonomy, array( 'grade' ) ) && 'groups' == $current_screen->post_type ) {
        $submenu_file = 'edit-tags.php?taxonomy=grade&post_type=groups';
    }

    return $submenu_file;
}

add_action('admin_head', 'pu_set_open_menu');

/**
 * Open the correct menu for taxonomy
 */
function pu_set_open_menu()
{
    $screen = get_current_screen();
    if( $screen->base === 'edit-tags' && ( $screen->taxonomy === 'grade' || $screen->taxonomy === 'classescat' || $screen->taxonomy === 'groups_grade' ) )
    {
        wp_enqueue_script( 'open-menu-parent', plugins_url('../admin/js/admin-menu.js', __FILE__ ), array('jquery') );
    }
}

// force use of templates from plugin folder
function force_template( $template )
{	
	
	if( is_singular( 'classes' ) ) {
        $template = plugin_dir_path( dirname( __FILE__ ) ) .'/templates/classes-classes-template.php';
    }

    if( is_singular( 'groups' ) ) {
        $template = plugin_dir_path( dirname( __FILE__ ) ) .'/templates/classes-groups-template.php';
    }
    
    if( is_singular( 'schedules' ) ) {
        $template = plugin_dir_path( dirname( __FILE__ ) ) .'/templates/classes-schedules-template.php';
    }
    
    if( is_tax( 'grade' ) || is_tax( 'classescat' ) || is_tax( 'groups_grade' ) ) {
        $template = plugin_dir_path( dirname( __FILE__ ) ) .'/templates/classes-taxonomy-template.php';
	}
	
  return $template;
  
}
add_filter( 'template_include', 'force_template' );

?>