<?php
/*
Template Name: Blog
Template Post Type: page
*
* @package AjaxinWP
*/

get_header(); 
?>

<div id="ajax-container">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="post">
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <div class="entry">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php the_posts_navigation(); ?>
                <?php else : ?>
                    <p><?php _e('No posts found.', 'ajaxinwp'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
