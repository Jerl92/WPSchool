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

function school_admin_menu() {
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
add_action( 'admin_menu', 'school_admin_menu' );
    
function school_dashboard_contents() { ?>
    <h1>
        <?php esc_html_e( 'School Dashboard', 'school' ); ?>
    </h1>
    <?php

        $terms = get_terms('classescat');
        if ($terms) {
            foreach ($terms as $term) {
                // The $term is an object, so we don't need to specify the $taxonomy.
                $term_link = get_term_link( $term );
                // If there was an error, continue to the next term.
                if ( is_wp_error( $term_link ) ) {
                    continue;
                }
                // We successfully got a link. Print it out.
                echo '<li><a href="' . esc_url( $term_link ) . '">' . $term->name . '</a></li>';
            }
        }

        $terms = get_terms('grade');
        if ($terms) {
            foreach ($terms as $term) {
                // The $term is an object, so we don't need to specify the $taxonomy.
                $term_link = get_term_link( $term );
                // If there was an error, continue to the next term.
                if ( is_wp_error( $term_link ) ) {
                    continue;
                }
                // We successfully got a link. Print it out.
                echo '<li><a href="' . esc_url( $term_link ) . '">' . $term->name . '</a></li>';
            }
        }

        echo 'Groupe</br>';
        $terms = get_terms('grade');
        if ($terms) {
            foreach ($terms as $term) {
                // The $term is an object, so we don't need to specify the $taxonomy.
				$get_groups_args = array(
					'post_type' => 'groups',
					'posts_per_page' => -1,
					'order' => 'ASC',
					'tax_query' => array(
						array(
							'taxonomy' => 'grade',
							'field'    => 'name',
							'terms'    => $term->name
						)
					)
				);
                $get_groups = get_posts($get_groups_args);
                echo $term->name;
                echo ' - ';
                foreach ( $get_groups as $group ) {
                    $get_users_in_group = get_post_meta($group->ID, "_user_in_group", true);
                    echo $group->post_title;
                    echo ' - ';
                    foreach ($get_users_in_group as $user) {
                        echo $user;
                        echo ' - ';
                    }
                }
                echo '</br>';
            }
        }

    ?>
<?php }

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