<?php
if (!function_exists('ajaxinwp_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
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

        // Add image sizes
        add_image_size('ajaxinwp-thumb', 400, 400, true); // Thumbnail size
        add_image_size('ajaxinwp-feature', 1080, 720, true); // Feature size
    }
endif;
add_action('after_setup_theme', 'ajaxinwp_setup');

/**
 * Get post thumbnail or fallback image.
 */
function get_post_thumbnail_or_fallback($post_id, $size = 'medium', $attr = '') {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size, $attr);
    } else {
        $default_image_url = get_template_directory_uri() . '/assets/img/fallback1080x720.jpg';
        return '<img src="' . esc_url($default_image_url) . '" alt="' . esc_attr__('Default Image', 'ajaxinwp') . '" class="attachment-' . esc_attr($size) . ' size-' . esc_attr($size) . ' wp-post-image">';
    }
}

 

/**
 * Enqueue theme styles and scripts.
 */
function ajaxinwp_styles_and_scripts() {
    // Enqueue styles
    wp_enqueue_style('bootstrap-css', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), filemtime(get_template_directory() . '/assets/css/bootstrap.min.css'), 'all');
    wp_enqueue_style('bootstrap-icons', get_template_directory_uri() . '/assets/css/bootstrap-icons.css', array(), filemtime(get_template_directory() . '/assets/css/bootstrap-icons.css'), 'all');
    wp_enqueue_style('ajaxinwp-editor-style', get_template_directory_uri() . '/assets/css/editor-style.css', array(), wp_get_theme()->get('Version'), 'all');
    wp_enqueue_style('ajaxinwp-general-style', get_template_directory_uri() . '/assets/css/general.css', [], wp_get_theme()->get('Version'));
    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/css/fontawesome.min.css', array(), filemtime(get_template_directory() . '/assets/css/fontawesome.min.css'));
    
    // Enqueue scripts
    wp_enqueue_script('font-awesome', get_template_directory_uri() . '/assets/js/fontawesome.js', array(), filemtime(get_template_directory() . '/assets/js/fontawesome.js'), true);
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap-js', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array('jquery'), filemtime(get_template_directory() . '/assets/js/bootstrap.bundle.min.js'), true);
    wp_enqueue_script('ajaxinwp-js', get_template_directory_uri() . '/assets/js/ajaxinwp.js', array('jquery'), wp_get_theme()->get('Version'), true);
    wp_enqueue_script('ajaxinwp-image-fallback', get_template_directory_uri() . '/assets/js/image-fallback.js', array('ajaxinwp-js'), wp_get_theme()->get('Version'), true);
    wp_enqueue_script('custom-logo-script', get_template_directory_uri() . '/assets/js/logo.js', [], wp_get_theme()->get('Version'), true);

    // Localize script
    wp_localize_script('ajaxinwp-js', 'ajaxinwp_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ajaxinwp_nonce'),
        'homeURL'  => get_home_url(),
        'isHome'   => is_home() || is_front_page(),
        'fallbackImage' => get_template_directory_uri() . '/assets/img/fallback1080x720.jpg'
    ));

    // Add inline script
    wp_add_inline_script('ajaxinwp-js', 'document.body.dataset.theme = "' . esc_js(get_theme_mod('ajaxinwp_color_scheme', 'auto')) . '";', 'before');

    // Enqueue comment reply script on singular pages with open comments
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'ajaxinwp_styles_and_scripts');

// Load additional theme files
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/helpers/bootstrap-menu-walker.php';
require_once get_template_directory() . '/helpers/bootstrap-comment-walker.php';
require_once get_template_directory() . '/inc/ajax-redirect.php';
require_once get_template_directory() . '/inc/css-generator.php';
require_once get_template_directory() . '/inc/widgets.php';

/**
 * Enqueue scripts for customizer preview.
 */
function ajaxinwp_customize_preview_js() {
    wp_enqueue_script('ajaxinwp_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array('customize-preview'), wp_get_theme()->get('Version'), true);
}
add_action('customize_preview_init', 'ajaxinwp_customize_preview_js');
 

/**
 * Add support for Gutenberg editor styles.
 */
function ajaxinwp_add_gutenberg_support() {
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');
}
add_action('after_setup_theme', 'ajaxinwp_add_gutenberg_support');

/**
 * Print HTML with meta information for the current post-date/time.
 */
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

/**
 * Print HTML with meta information for the current post author.
 */
if (!function_exists('ajaxinwp_posted_by')) :
    function ajaxinwp_posted_by() {
        $byline = sprintf(
            esc_html_x('by %s', 'post author', 'ajaxinwp'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>';
    }
endif;

/**
 * Print HTML with meta information for the categories, tags and comments.
 */
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

/**
 * Handle AJAX requests and load the appropriate content.
 */
function ajaxinwp_handle_ajax_requests() {
    if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
        ob_start();

        if (is_page()) {
            while (have_posts()) :
                the_post();
                echo '<div id="ajax-container">';
                get_template_part('partials/partials-content-page', get_post_format());
                echo '</div>';
            endwhile;
        } elseif (is_single()) {
            while (have_posts()) :
                the_post();
                echo '<div id="ajax-container">';
                get_template_part('partials/partials-content-single', get_post_format());
                echo '</div>';
            endwhile;
        } elseif (is_category()) {
            echo '<div id="ajax-container">';
            get_template_part('partials/partials-content-category', get_post_format());
            echo '</div>';
        } elseif (is_archive()) {
            echo '<div id="ajax-container">';
            get_template_part('partials/partials-content-archive', get_post_format());
            echo '</div>';
        } else {
            echo '<div id="ajax-container">';
            get_template_part('partials/partials-content-home');
            echo '</div>';
        }

        $content = ob_get_clean();
        echo $content;
        exit;
    }
}
add_action('template_redirect', 'ajaxinwp_handle_ajax_requests');

/**
 * Ensure images are cropped to the specified sizes.
 */
function ajaxinwp_ensure_image_crops($metadata, $attachment_id) {
    $sizes = ['ajaxinwp-thumb', 'ajaxinwp-feature'];
    foreach ($sizes as $size) {
        if (!isset($metadata['sizes'][$size])) {
            $image_path = get_attached_file($attachment_id);
            $editor = wp_get_image_editor($image_path);
            if (!is_wp_error($editor)) {
                $editor->resize(get_option("{$size}_size_w"), get_option("{$size}_size_h"), true);
                $resized = $editor->save();
                if (!is_wp_error($resized)) {
                    $metadata['sizes'][$size] = [
                        'file' => basename($resized['path']),
                        'width' => $resized['width'],
                        'height' => $resized['height'],
                        'mime-type' => $resized['mime-type'],
                    ];
                }
            }
        }
    }
    return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'ajaxinwp_ensure_image_crops', 10, 2);

/**
 * Register custom block patterns.
 */
function ajaxinwp_register_block_patterns() {
    register_block_pattern_category('ajaxinwp', array('label' => __('AjaxInWP', 'ajaxinwp')));


    $pattern_dir = get_template_directory() . '/patterns';
    foreach (glob($pattern_dir . '/*.html') as $file) {
        $slug  = 'ajaxinwp/' . basename($file, '.html');
        $title = ucwords(str_replace('-', ' ', basename($file, '.html')));

        register_block_pattern(
            $slug,
            array(
                'title'      => $title,
                'categories' => array('ajaxinwp'),
                'content'    => file_get_contents($file),
            )
        );
    }
}
add_action('init', 'ajaxinwp_register_block_patterns');

/**
 * Insert a dynamic table of contents for posts.
 */
function ajaxinwp_add_table_of_contents($content) {
    if (is_singular('post') && in_the_loop() && is_main_query()) {
        if (preg_match_all('/<h([2-3])[^>]*>(.*?)<\/h\1>/', $content, $matches)) {
            $toc = '<nav class="ajaxinwp-toc"><strong>' . esc_html__('Contents', 'ajaxinwp') . '</strong><ol>';
            foreach ($matches[2] as $index => $heading) {
                $slug = 'toc-' . ($index + 1);
                $content = str_replace($matches[0][$index], '<h' . $matches[1][$index] . ' id="' . esc_attr($slug) . '">' . $heading . '</h' . $matches[1][$index] . '>', $content);
                $toc .= '<li><a href="#' . esc_attr($slug) . '">' . wp_strip_all_tags($heading) . '</a></li>';
            }
            $toc .= '</ol></nav>';
            return $toc . $content;
        }
    }
    return $content;
}
add_filter('the_content', 'ajaxinwp_add_table_of_contents');

?>

