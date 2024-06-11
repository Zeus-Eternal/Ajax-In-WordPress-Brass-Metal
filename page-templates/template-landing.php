<?php
/*
Template Name: Landing Page
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
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', 'page');
                endwhile;
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
