<?php
/*
Template Name: With Sidebar
Template Post Type: page
*
* @package AjaxinWP
*/

get_header(); 
?>

<div class="container mt-5">
    <div class="row">
        <!-- Main content area -->
        <div class="col-lg-8">
            <div id="ajax-container">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/partials-content-page');
                endwhile;
                ?>
            </div>
        </div>

        <!-- Sidebar area -->
        <div class="col-lg-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
