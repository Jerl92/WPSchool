<?php function school_dashboard_contents() { ?>
    <h1>
        <?php esc_html_e( 'School Dashboard', 'school' ); ?>
    </h1>
    <?php
        echo '<div class="grid">';
            echo '<div class="grid-item">';
                echo '</br>Grade</br>';
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

                        $posts_array = get_posts(
                            array(
                                'posts_per_page' => 1,
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

                        foreach ($posts_array as $post) {
                            $terms = get_the_terms( $post->ID, 'classescat' );
                            foreach ( $terms as $term ) {
                                echo '<li><a href="' .  get_term_link($term) . '">' . $term->name . '</a></li>';
                            }
                        }

                    }
                }
            echo '</div>';

            echo '<div class="grid-item">';
                echo '</br>Groupe</br>';
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
                            foreach ($get_users_in_group as $user) {
                                echo $user;
                                echo ' - ';
                            }
                            echo '</br>';
                        }
                    }
                }
            echo '</div>';
        echo '</div>';
    ?>
<?php }