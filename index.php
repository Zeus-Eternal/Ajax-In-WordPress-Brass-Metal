<?php
/**
 * The main theme file for AjaxinWP, adapted for Bootstrap 5.3 compatibility.
 * Developed by Zeus Eternal
 *
 * This file is used to display content based on the theme's content layout settings.
 * It supports different content layouts including right sidebar, left sidebar, and no sidebar.
 */

get_header(); // Load the header template

// Define container class based on theme mod setting
$content_layout = get_theme_mod('ajaxinwp_content_layout', 'right-sidebar');
$container_class = strpos($content_layout, 'fluid') !== false ? 'container-fluid' : 'container';

// Define content and sidebar classes based on content layout
$content_classes = 'col-lg-8';
$sidebar_classes = 'col-lg-4';
if ($content_layout === 'right-sidebar' || $content_layout === 'right-sidebar-fluid') {
    // If right sidebar layout, float content to the right
    $content_classes .= ' float-end';
} elseif ($content_layout === 'left-sidebar' || $content_layout === 'left-sidebar-fluid') {
    // If left sidebar layout, float content to the left and order sidebar first
    $content_classes .= ' float-start';
    $sidebar_classes .= ' order-first';
} elseif ($content_layout === 'no-sidebar') {
    // If no sidebar layout, content takes full width
    $content_classes = 'col-lg-12';
    $sidebar_classes = '';
}
?>

<div id="content" class="site-content mt-5 <?php echo esc_attr($container_class); ?>">
    <div id="primary" class="content-area row theme-content">
        <?php if ($content_layout !== 'no-sidebar' && strpos($content_layout, 'left-sidebar') !== false) : ?>
            <!-- Sidebar on the left -->
            <aside class="<?php echo esc_attr($sidebar_classes); ?>">
                <?php get_sidebar(); // Load the sidebar template ?>
            </aside>
        <?php endif; ?>

        <!-- Main content area -->
        <main id="main" class="<?php echo esc_attr($content_classes); ?>" role="main">
            <div id="ajax-container">
                <?php
                // Load initial content if necessary
                if (!is_ajax_request()) {
                    if (is_page()) {
                        // Load page content
                        while (have_posts()) : the_post();
                            get_template_part('partials/partials-content-page', get_post_format());
                        endwhile;
                    } elseif (is_single()) {
                        // Load single post content
                        while (have_posts()) : the_post();
                            get_template_part('partials/partials-content-single', get_post_format());
                        endwhile;
                    } elseif (is_category()) {
                        // Load category content
                        while (have_posts()) : the_post();
                            get_template_part('partials/partials-content-category', get_post_format());
                        endwhile;
                    } elseif (is_archive()) {
                        // Load archive content
                        while (have_posts()) : the_post();
                            get_template_part('partials/partials-content-archive', get_post_format());
                        endwhile;
                    } else {
                        // Load home content
                        get_template_part('partials/partials-content-home');
                    }
                }
                ?>
            </div>
        </main>

        <?php if ($content_layout !== 'no-sidebar' && strpos($content_layout, 'right-sidebar') !== false) : ?>
            <!-- Sidebar on the right -->
            <aside class="<?php echo esc_attr($sidebar_classes); ?>">
                <?php get_sidebar(); // Load the sidebar template ?>
            </aside>
        <?php endif; ?>
    </div><!-- #primary -->
</div><!-- .site-content -->

<div class="card-footer">
    <?php get_template_part('partials/partials-widgets'); // Load the footer widgets ?>
</div><!-- .card-footer -->

<?php get_footer(); // Load the footer template ?>
