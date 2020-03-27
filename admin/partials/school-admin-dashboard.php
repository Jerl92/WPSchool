<?php function school_dashboard_contents() { ?>
    <h1>
        <?php esc_html_e( 'School Dashboard', 'school' ); ?>
    </h1>
    <?php
        echo '<div class="grid">';
            echo '<div class="grid-item">';
                echo '<h2 class="hndle ui-sortable-handle"><span>Grade</span></h2>';
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
                        echo '<span><a href="' . esc_url( $term_link ) . '">' . $term->name . '</a></span></br>';

                        $posts_array = get_posts(
                            array(
                                'posts_per_page' => -1,
                                'post_type' => 'classes',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'grade',
                                        'field' => 'term_id',
                                        'terms' => $term->term_id,
                                    )
                                )
                            )
                        );

                        $terms_allready = array();
                        foreach ($posts_array as $post) {
                            $terms = get_the_terms( $post->ID, 'classescat' );
                            foreach ( $terms as $term ) {
                                if (!in_array($term->slug, $terms_allready)) {
                                    array_push( $terms_allready, $term->slug );
                                    echo '<span> - <a href="' .  get_term_link($term) . '">' . $term->name . '</a></span></br>';
                                }
                            }
                        }
                        echo '</br>';
                    }
                }
            echo '</div>';

            echo '<div class="grid-item">';
                echo '<h2 class="hndle ui-sortable-handle"><span>Groupe</span></h2>';
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
                        echo '</br>';
                        foreach ( $get_groups as $group ) {
                            $get_users_in_group = get_post_meta($group->ID, "_user_in_group", true);
                            echo $group->post_title;
                            echo '</br>';

                            foreach ($get_users_in_group as $user_id) {
                                $user = get_user_by('id', $user_id);
                                echo $user->user_nicename;
                                echo ' - ';
                            }
                            echo '</br>';

                        }
                    }
                }
            echo '</div>';

            echo '<div class="grid-item">';
                echo '<h2 class="hndle ui-sortable-handle"><span>User</span></h2>';
                foreach (get_editable_roles() as $role_name => $role_info) {

                    $args = array(
                        'role'    => $role_name,
                        'orderby' => 'user_nicename',
                        'order'   => 'ASC'
                    );
                    $users = get_users( $args );
                    
                    echo '<ul>';
                    echo $role_name;
                    foreach ( $users as $user ) {
                        echo '<li> - ' . esc_html( $user->display_name ) . '</li>';
                    }
                    echo '</ul>';

                }

            echo '</div>';

        echo '</div>';
    ?>
<?php }