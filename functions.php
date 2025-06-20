<?php

/**
 * Get post thumbnail or fallback image.
 */
function get_post_thumbnail_or_fallback( $post_id, $size = 'medium', $attr = '' ) {
    if ( ! get_theme_mod( 'ajaxinwp_show_featured', true ) ) {
        return '';
    }

    if ( has_post_thumbnail( $post_id ) ) {
        return get_the_post_thumbnail( $post_id, $size, $attr );
    }

    $fallback = get_theme_mod( 'ajaxinwp_fallback_image' );
    if ( ! $fallback ) {
        $fallback = get_template_directory_uri() . '/assets/img/fallback1080x720.jpg';
    }

    return '<img src="' . esc_url( $fallback ) . '" alt="' . esc_attr__( 'Default Image', 'ajaxinwp' ) . '" class="attachment-' . esc_attr( $size ) . ' size-' . esc_attr( $size ) . ' wp-post-image">';
}

// Load helper files.
require_once get_template_directory() . '/helpers/bootstrap-menu-walker.php';
require_once get_template_directory() . '/helpers/bootstrap-comment-walker.php';

// Load OOP modules.
require_once get_template_directory() . '/inc/class-ajaxinwp-theme.php';
require_once get_template_directory() . '/inc/class-ajaxinwp-customizer.php';
require_once get_template_directory() . '/inc/class-ajaxinwp-css-generator.php';
require_once get_template_directory() . '/inc/class-ajaxinwp-widgets.php';

AjaxinWP_Theme::get_instance();
AjaxinWP_Customizer::init();
AjaxinWP_CSS_Generator::init();
AjaxinWP_Widgets::init();

if ( ! function_exists( 'is_ajax_request' ) ) {
    /**
     * Check if the current request is an AJAX call.
     *
     * @return bool True when the request is via XHR.
     */
    function is_ajax_request() {
        return isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) &&
            strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest';
    }
}

/**
 * Print HTML with meta information for the current post-date/time.
 */
if ( ! function_exists( 'ajaxinwp_posted_on' ) ) :
    function ajaxinwp_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr( get_the_date( DATE_W3C ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( DATE_W3C ) ),
            esc_html( get_the_modified_date() )
        );

        $posted_on = sprintf(
            esc_html_x( 'Posted on %s', 'post date', 'ajaxinwp' ),
            '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span>';
    }
endif;

/**
 * Add quick links to design tools in the admin bar.
 */
function ajaxinwp_admin_bar_links( $wp_admin_bar ) {
    if ( ! current_user_can( 'edit_theme_options' ) ) {
        return;
    }

    $parent = 'site-name';

    if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
        $wp_admin_bar->add_node(
            [
                'parent' => $parent,
                'id'     => 'ajaxinwp-site-editor',
                'title'  => __( 'Site Editor', 'ajaxinwp' ),
                'href'   => admin_url( 'site-editor.php' ),
            ]
        );
    } else {
        $wp_admin_bar->add_node(
            [
                'parent' => $parent,
                'id'     => 'ajaxinwp-customize',
                'title'  => __( 'Customize', 'ajaxinwp' ),
                'href'   => admin_url( 'customize.php' ),
            ]
        );
    }

    $file_edit_allowed = ! defined( 'DISALLOW_FILE_EDIT' ) || ! DISALLOW_FILE_EDIT;
    if ( current_user_can( 'edit_themes' ) && $file_edit_allowed ) {
        $wp_admin_bar->add_node(
            [
                'parent' => $parent,
                'id'     => 'ajaxinwp-theme-editor',
                'title'  => __( 'Theme File Editor', 'ajaxinwp' ),
                'href'   => admin_url( 'theme-editor.php' ),
            ]
        );
    }
}
add_action( 'admin_bar_menu', 'ajaxinwp_admin_bar_links', 80 );

/**
 * Print HTML with meta information for the current post author.
 */
if ( ! function_exists( 'ajaxinwp_posted_by' ) ) :
    function ajaxinwp_posted_by() {
        $byline = sprintf(
            esc_html_x( 'by %s', 'post author', 'ajaxinwp' ),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>';
    }
endif;

/**
 * Print HTML with meta information for the categories, tags and comments.
 */
if ( ! function_exists( 'ajaxinwp_entry_footer' ) ) :
    function ajaxinwp_entry_footer() {
        if ( 'post' === get_post_type() ) {
            $categories_list = get_the_category_list( esc_html__( ', ', 'ajaxinwp' ) );
            if ( $categories_list ) {
                printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'ajaxinwp' ) . '</span> | ', $categories_list );
            }

            $tags_list = get_the_tag_list( '', esc_html__( ', ', 'ajaxinwp' ) );
            if ( $tags_list ) {
                printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'ajaxinwp' ) . '</span>', $tags_list );
            }
        }

        if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'ajaxinwp' ),
                        array( 'span' => array( 'class' => array() ) )
                    ),
                    get_the_title()
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    __( 'Edit <span class="screen-reader-text">%s</span>', 'ajaxinwp' ),
                    array( 'span' => array( 'class' => array() ) )
                ),
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;

