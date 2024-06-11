<?php
/*
Template Name: 404 Error
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
                <?php if (!is_ajax_request()) : ?>
                    <section class="error-404 not-found">
                        <header class="page-header">
                            <h1 class="page-title"><?php esc_html_e('Oops! That page can’t be found.', 'ajaxinwp'); ?></h1>
                        </header>
                        <div class="page-content">
                            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'ajaxinwp'); ?></p>
                            <?php get_search_form(); ?>
                        </div>
                    </section>
                <?php endif; ?>
            </div>
        </main>
    </div><!-- #primary -->
</div><!-- .site-content -->

<?php get_footer(); ?>
