<?php
/*
Template Name: Full-width page layout
Template Post Type: post, page, product, schedules
*/
?>
 
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
                        <?php                         
                            $get_post_classes_in_schedule = get_post_meta( get_the_ID(), '_classes_in_schedule', true );
                            if ( $get_post_classes_in_schedule ) {
                                foreach ($get_post_classes_in_schedule as $classeid ) {
                                    $days[] = array(
                                        'id'  =>  $classeid['classesid'],
                                        'day'  =>  $classeid['day'],
                                        'start'  =>  $classeid['starttime'],
                                        'end'  =>  $classeid['endtime']
                                    );
                                }
                                foreach ($days as $day) {
                                    $day_index[] = $day['day'];
                                }
                                $daymax = max($day_index);
                                echo '<table style="width:100%;">';
                                for ($x = 1; $x <= $daymax; null) {
                                        echo '<tr>';
                                        for ($i = 0; $i <= 4; $i++) {
                                            echo '<th>';
                                            if ($x <= $daymax) {
                                                echo 'Day ' . $x . '';
                                            }
                                            echo '</th>';
                                            $x++;
                                        }
                                        echo '</tr>';
                                        echo '<tr>';
                                        $x = $x - 5;
                                        for ($i = 0; $i <= 4; $i++) {
                                                echo '<td>';
                                                if ($x <= $daymax) {
                                                    foreach ($days as $day_) {
                                                        if ($x == $day_['day']) {
                                                            $classe_post = get_post($day_['id']);
                                                            echo $classe_post->post_title;
                                                            echo ' - ';
                                                            echo $day_['start'];
                                                            echo ' - ';
                                                            echo $day_['end'];
                                                            echo ' - ';
                                                            echo '</br>';
                                                        }
                                                    }
                                                }
                                                echo '</td>';
                                                $x++;
                                        }
                                        echo '</tr>';
                                }
                                echo '</table>';
                            }
                        ?>
                    </div>
                </div>

                <?php endwhile; // end of the loop. ?>

            </article>
        </div>
    </div>

<?php get_footer();