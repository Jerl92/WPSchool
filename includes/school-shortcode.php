<?php

function classes_by_category() {
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
}

add_shortcode('get_classes_by_category', 'classes_by_category');

function classes_by_grade() {
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
}

add_shortcode('get_classes_by_grade', 'classes_by_grade');

?>