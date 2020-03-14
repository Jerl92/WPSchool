<?php /* Template Name: CustomPageT1 */ ?>
 
<?php get_header(); ?>

    <div id="loop-container" class="loop-container">
        <div class="page type-page status-publish hentry entry">
            <article>

                <?php while ( have_posts() ) : the_post(); ?>

                <div class="post-container">
                    <div class="post-header">
                        <h3 class="post-title"><?php the_title(); ?></h3>
                    </div>
                    <div class="post-content">
                        <?php // Returns Array of Term Names for "my_taxonomy".
                        $term_list = get_the_terms( $post->ID, 'grade' );
                        foreach ($term_list as $term) {
                            echo $term->name;
                            echo ' </br> ';
                            echo ' </br> ';
                        } ?>
                        <?php $get_users_in_group = get_post_meta(get_the_id(), "_user_in_group", true); ?>
                        <?php foreach ($get_users_in_group as $user_in_group) {
                            $user = get_user_by('id',  $user_in_group);
                            echo $user->user_nicename;
                            echo ' - ';
                            echo $user->user_email;
                            echo ' </br> ';
                        } ?>
                    </div>
                </div>

                <?php endwhile; // end of the loop. ?>

            </article>
        </div>
    </div>

<?php get_footer();