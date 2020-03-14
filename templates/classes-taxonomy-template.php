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
                        <?php the_content(); ?>
                    </div>
                </div>

                <?php endwhile; // end of the loop. ?>

            </article>
        </div>
    </div>

<?php get_footer();