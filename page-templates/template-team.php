<?php
/*
Template Name: Team
Template Post Type: page
*/

get_header();
?>

<div class="container team-page">
    <main id="main" class="site-main" role="main">
        <?php
        while (have_posts()) :
            the_post();
            get_template_part('template-parts/content', 'page');
        endwhile;
        ?>

        <!-- Team Members -->
        <section class="team-members">
            <h2><?php _e('Our Team', 'ajaxinwp'); ?></h2>
            <div class="row">
                <?php
                $team_members = new WP_Query(array(
                    'post_type' => 'post',
                    'category_name' => 'team',
                    'posts_per_page' => -1,
                ));
                if ($team_members->have_posts()) :
                    while ($team_members->have_posts()) : $team_members->the_post();
                        // Parse the excerpt to extract additional info
                        $additional_info = explode("\n", get_the_excerpt());
                        ?>
                        <div class="col-md-4">
                            <div class="team-member">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="team-member-image">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </div>
                                <?php endif; ?>
                                <h3><?php the_title(); ?></h3>
                                <ul class="additional-info">
                                    <?php foreach ($additional_info as $info) : ?>
                                        <li><?php echo esc_html($info); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <p><?php echo wp_trim_words(get_the_content(), 40, '...'); ?></p>
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php _e('Read More', 'ajaxinwp'); ?></a>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>' . __('No team members found.', 'ajaxinwp') . '</p>';
                endif;
                ?>
            </div>
        </section>
    </main>
</div>

<?php
get_footer();
?>
