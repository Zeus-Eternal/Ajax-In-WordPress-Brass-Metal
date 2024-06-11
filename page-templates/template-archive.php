<?php
/*
Template Name: Archive
Template Post Type: page
*
* @package AjaxinWP
*/

get_header();
?>

<div class="container site-content mt-5">
    <div id="primary" class="content-area row theme-content">
        <main id="main" class="col-lg-12" role="main">
            <div id="ajax-container">
                <?php
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                );

                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="post">
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <div class="entry">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>

                    <nav class="navigation pagination">
                        <?php
                        echo paginate_links(array(
                            'total' => $query->max_num_pages,
                            'current' => $paged,
                            'prev_text' => __('« Previous', 'ajaxinwp'),
                            'next_text' => __('Next »', 'ajaxinwp'),
                        ));
                        ?>
                    </nav>
                <?php else : ?>
                    <p><?php esc_html_e('No posts found.', 'ajaxinwp'); ?></p>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </main>
    </div><!-- #primary -->
</div><!-- .site-content -->

<?php get_footer(); ?>
