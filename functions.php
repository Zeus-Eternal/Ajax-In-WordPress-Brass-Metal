<?php
if (!function_exists('ajaxinwp_setup')) :
    function ajaxinwp_setup() {
        load_theme_textdomain('ajaxinwp', get_template_directory() . '/languages');
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('custom-logo', array(
            'height'      => 'auto',
            'width'       => 400,
            'flex-width'  => true,
            'flex-height' => true,
        ));
        add_theme_support('align-wide');
        add_theme_support('responsive-embeds');
        add_theme_support('wp-block-styles');
        add_theme_support('block-templates');
        add_theme_support('editor-styles');
        add_editor_style('assets/css/editor-style.css');

        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'ajaxinwp'),
            'top'     => esc_html__('Top Menu', 'ajaxinwp'),
            'footer'  => esc_html__('Footer Menu', 'ajaxinwp'),
        ));

        add_image_size('ajaxinwp-thumb', 400, 400, true);
        add_image_size('ajaxinwp-feature', 1080, 720, true);
    }
endif;
add_action('after_setup_theme', 'ajaxinwp_setup');

function get_post_thumbnail_or_fallback($post_id, $size = 'medium', $attr = '') {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size, $attr);
    } else {
        $default_image_url = get_template_directory_uri() . '/assets/img/fallback1080x720.jpg';
        return '<img src="' . esc_url($default_image_url) . '" alt="' . esc_attr__('Default Image', 'ajaxinwp') . '" class="attachment-' . esc_attr($size) . ' size-' . esc_attr($size) . ' wp-post-image">';
    }
}


function ajaxinwp_add_gutenberg_support() {
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');
}
add_action('after_setup_theme', 'ajaxinwp_add_gutenberg_support');

if (!function_exists('ajaxinwp_posted_on')) :
    function ajaxinwp_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf($time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );

        $posted_on = sprintf(
            esc_html_x('Posted on %s', 'post date', 'ajaxinwp'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span>';
    }
endif;

if (!function_exists('ajaxinwp_posted_by')) :
    function ajaxinwp_posted_by() {
        $byline = sprintf(
            esc_html_x('by %s', 'post author', 'ajaxinwp'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>';
    }
endif;

if (!function_exists('ajaxinwp_entry_footer')) :
    function ajaxinwp_entry_footer() {
        if ('post' === get_post_type()) {
            $categories_list = get_the_category_list(esc_html__(', ', 'ajaxinwp'));
            if ($categories_list) {
                printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'ajaxinwp') . '</span> | ', $categories_list);
            }

            $tags_list = get_the_tag_list('', esc_html__(', ', 'ajaxinwp'));
            if ($tags_list) {
                printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'ajaxinwp') . '</span>', $tags_list);
            }
        }

        if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'ajaxinwp'),
                        array('span' => array('class' => array()))
                    ),
                    get_the_title()
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    __('Edit <span class="screen-reader-text">%s</span>', 'ajaxinwp'),
                    array('span' => array('class' => array()))
                ),
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;

require_once get_template_directory() . '/inc/class-ajaxinwp-theme.php';
AjaxinWP_Theme::get_instance();
?>

