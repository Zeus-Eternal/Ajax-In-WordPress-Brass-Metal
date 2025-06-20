<?php
/**
 * AjaxinWP Theme class for registering assets and block patterns.
 */
class AjaxinWP_Theme {
    /** Singleton instance */
    private static $instance;

    /**
     * Get the singleton instance.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /** Constructor - register hooks */
    private function __construct() {
        add_action( 'after_setup_theme', [ $this, 'setup_theme' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'customize_preview_init', [ $this, 'customize_preview_js' ] );
        add_action( 'template_redirect', [ $this, 'handle_ajax_requests' ] );
        add_filter( 'wp_generate_attachment_metadata', [ $this, 'ensure_image_crops' ], 10, 2 );
        add_filter( 'wp_get_attachment_image_attributes', [ $this, 'image_fallback_attr' ] );
        add_action( 'init', [ $this, 'register_block_patterns' ] );
        add_filter( 'the_content', [ $this, 'add_table_of_contents' ] );
        add_action( 'admin_head', [ $this, 'admin_styles' ] );
    }

    /**
     * Enqueue theme styles and scripts.
     */
    public function enqueue_assets() {
        wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/assets/css/bootstrap.min.css', [], filemtime( get_template_directory() . '/assets/css/bootstrap.min.css' ), 'all' );
        wp_enqueue_style( 'bootstrap-icons', get_template_directory_uri() . '/assets/css/bootstrap-icons.css', [], filemtime( get_template_directory() . '/assets/css/bootstrap-icons.css' ), 'all' );
        wp_enqueue_style( 'ajaxinwp-editor-style', get_template_directory_uri() . '/assets/css/editor-style.css', [], wp_get_theme()->get( 'Version' ), 'all' );
        wp_enqueue_style( 'ajaxinwp-general-style', get_template_directory_uri() . '/assets/css/general.css', [], wp_get_theme()->get( 'Version' ) );
        wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/fontawesome.min.css', [], filemtime( get_template_directory() . '/assets/css/fontawesome.min.css' ) );

        wp_enqueue_script( 'font-awesome', get_template_directory_uri() . '/assets/js/fontawesome.js', [], filemtime( get_template_directory() . '/assets/js/fontawesome.js' ), true );
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', [ 'jquery' ], filemtime( get_template_directory() . '/assets/js/bootstrap.bundle.min.js' ), true );
        wp_enqueue_script( 'ajaxinwp-js', get_template_directory_uri() . '/assets/js/ajaxinwp.js', [ 'jquery' ], wp_get_theme()->get( 'Version' ), true );
        wp_enqueue_script( 'image-fallback', get_template_directory_uri() . '/assets/js/image-fallback.js', [], wp_get_theme()->get( 'Version' ), true );
        wp_enqueue_script( 'custom-logo-script', get_template_directory_uri() . '/assets/js/logo.js', [], wp_get_theme()->get( 'Version' ), true );

        $fallback = get_theme_mod( 'ajaxinwp_fallback_image' );
        if ( ! $fallback ) {
            $fallback = get_template_directory_uri() . '/assets/img/fallback1080x720.jpg';
        }

        wp_localize_script( 'ajaxinwp-js', 'ajaxinwp_params', [
            'ajax_url'      => admin_url( 'admin-ajax.php' ),
            'nonce'         => wp_create_nonce( 'ajaxinwp_nonce' ),
            'homeURL'       => get_home_url(),
            'isHome'        => is_home() || is_front_page(),
            'fallbackImage' => esc_url( $fallback ),
        ] );

        $logo_light = '';
        $logo_data  = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
        if ( is_array( $logo_data ) ) {
            $logo_light = $logo_data[0];
        }
        wp_localize_script( 'custom-logo-script', 'ajaxinwp_logo', [
            'dark'  => esc_url( get_theme_mod( 'ajaxinwp_logo_dark' ) ),
            'light' => esc_url( $logo_light ),
        ] );

        wp_add_inline_script( 'ajaxinwp-js', 'document.body.dataset.theme = "' . esc_js( get_theme_mod( 'ajaxinwp_color_scheme', 'auto' ) ) . '";', 'before' );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }

    /** Enqueue scripts for customizer preview */
    public function customize_preview_js() {
        wp_enqueue_script( 'ajaxinwp_customizer', get_template_directory_uri() . '/assets/js/customizer.js', [ 'customize-preview' ], wp_get_theme()->get( 'Version' ), true );
    }

    /** Handle AJAX requests and load appropriate content */
    public function handle_ajax_requests() {
        if ( isset( $_GET['ajax'] ) && '1' === $_GET['ajax'] ) {
            ob_start();
            if ( is_page() ) {
                while ( have_posts() ) {
                    the_post();
                    echo '<div id="ajax-container">';
                    get_template_part( 'partials/partials-content-page', get_post_format() );
                    echo '</div>';
                }
            } elseif ( is_single() ) {
                while ( have_posts() ) {
                    the_post();
                    echo '<div id="ajax-container">';
                    get_template_part( 'partials/partials-content-single', get_post_format() );
                    echo '</div>';
                }
            } elseif ( is_category() ) {
                echo '<div id="ajax-container">';
                get_template_part( 'partials/partials-content-category', get_post_format() );
                echo '</div>';
            } elseif ( is_archive() ) {
                echo '<div id="ajax-container">';
                get_template_part( 'partials/partials-content-archive', get_post_format() );
                echo '</div>';
            } else {
                echo '<div id="ajax-container">';
                get_template_part( 'partials/partials-content-home' );
                echo '</div>';
            }
            $content = ob_get_clean();
            echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            exit;
        }
    }

    /** Ensure images are cropped to specified sizes */
    public function ensure_image_crops( $metadata, $attachment_id ) {
        $sizes = [ 'ajaxinwp-thumb', 'ajaxinwp-feature' ];
        foreach ( $sizes as $size ) {
            if ( ! isset( $metadata['sizes'][ $size ] ) ) {
                $image_path = get_attached_file( $attachment_id );
                $editor     = wp_get_image_editor( $image_path );
                if ( ! is_wp_error( $editor ) ) {
                    if ( 'ajaxinwp-feature' === $size ) {
                        $width  = absint( get_theme_mod( 'ajaxinwp_feature_width', 1080 ) );
                        $height = absint( get_theme_mod( 'ajaxinwp_feature_height', 720 ) );
                        $crop   = get_theme_mod( 'ajaxinwp_feature_crop', true ) ? true : false;
                    } else {
                        $width  = get_option( "{$size}_size_w" );
                        $height = get_option( "{$size}_size_h" );
                        $crop   = true;
                    }
                    $editor->resize( $width, $height, $crop );
                    $resized = $editor->save();
                    if ( ! is_wp_error( $resized ) ) {
                        $metadata['sizes'][ $size ] = [
                            'file'      => basename( $resized['path'] ),
                            'width'     => $resized['width'],
                            'height'    => $resized['height'],
                            'mime-type' => $resized['mime-type'],
                        ];
                    }
                }
            }
        }
        return $metadata;
    }

    /** Register block patterns from the patterns directory */
    public function register_block_patterns() {
        register_block_pattern_category( 'ajaxinwp', [ 'label' => __( 'AjaxInWP', 'ajaxinwp' ) ] );
        $pattern_dir = get_template_directory() . '/patterns';
        foreach ( glob( $pattern_dir . '/*.html' ) as $file ) {
            $slug  = 'ajaxinwp/' . basename( $file, '.html' );
            $title = ucwords( str_replace( '-', ' ', basename( $file, '.html' ) ) );
            register_block_pattern(
                $slug,
                [
                    'title'      => $title,
                    'categories' => [ 'ajaxinwp' ],
                    'content'    => file_get_contents( $file ),
                ]
            );
        }
    }

    /** Add a table of contents to post content */
    public function add_table_of_contents( $content ) {
        if ( is_singular() && in_the_loop() && is_main_query() ) {
            if ( preg_match_all( '/<h([2-3])[^>]*>(.*?)<\/h\1>/', $content, $matches ) ) {
                $toc = '<nav class="ajaxinwp-toc"><strong>' . esc_html__( 'Contents', 'ajaxinwp' ) . '</strong><ol>';
                foreach ( $matches[2] as $index => $heading ) {
                    $slug    = sanitize_title( wp_strip_all_tags( $heading ) );
                    $content = str_replace( $matches[0][ $index ], '<h' . $matches[1][ $index ] . ' id="' . esc_attr( $slug ) . '">' . $heading . '</h' . $matches[1][ $index ] . '>', $content );
                    $toc    .= '<li><a href="#' . esc_attr( $slug ) . '">' . wp_strip_all_tags( $heading ) . '</a></li>';
                }
                $toc .= '</ol></nav>';
                return $toc . $content;
            }
        }

        return $content;
    }

    /**
     * Theme setup.
     */
    public function setup_theme() {
        load_theme_textdomain( 'ajaxinwp', get_template_directory() . '/languages' );
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'customize-selective-refresh-widgets' );
        add_theme_support( 'custom-logo', [
            'height'      => 'auto',
            'width'       => 400,
            'flex-width'  => true,
            'flex-height' => true,
        ] );
        add_theme_support( 'align-wide' );
        add_theme_support( 'responsive-embeds' );
        add_theme_support( 'wp-block-styles' );
        add_theme_support( 'block-templates' );
        add_theme_support( 'editor-styles' );
        add_editor_style( 'assets/css/editor-style.css' );

        register_nav_menus(
            [
                'primary' => esc_html__( 'Primary Menu', 'ajaxinwp' ),
                'top'     => esc_html__( 'Top Menu', 'ajaxinwp' ),
                'footer'  => esc_html__( 'Footer Menu', 'ajaxinwp' ),
            ]
        );

        add_image_size( 'ajaxinwp-thumb', 400, 400, true );

        $feature_w   = absint( get_theme_mod( 'ajaxinwp_feature_width', 1080 ) );
        $feature_h   = absint( get_theme_mod( 'ajaxinwp_feature_height', 720 ) );
        $feature_crop = get_theme_mod( 'ajaxinwp_feature_crop', true ) ? true : false;
        add_image_size( 'ajaxinwp-feature', $feature_w, $feature_h, $feature_crop );
    }

    /**
     * Add onerror fallback to attachment images.
     */
    public function image_fallback_attr( $attr ) {
        $fallback = get_theme_mod( 'ajaxinwp_fallback_image' );
        if ( ! $fallback ) {
            $fallback = get_template_directory_uri() . '/assets/img/fallback1080x720.jpg';
        }
        $attr['onerror'] = "this.onerror=null;this.dataset.fallbackLoaded=true;this.src='" . esc_js( $fallback ) . "'";
        return $attr;
    }

 

 

 

    /**
     * Apply theme colors to WordPress admin area for better UX.
     */
    public function admin_styles() {
        $primary   = sanitize_hex_color( get_theme_mod( 'ajaxinwp_admin_primary', '#0d6efd' ) );
        $secondary = sanitize_hex_color( get_theme_mod( 'ajaxinwp_admin_secondary', '#6c757d' ) );
        echo '<style>
            #adminmenu, #wpadminbar { background:' . esc_attr( $primary ) . '; }
            #adminmenu .wp-submenu, #adminmenu .wp-has-current-submenu .wp-submenu { background:' . esc_attr( $secondary ) . '; }
            #adminmenu a, #wpadminbar a { color:#fff; }
        </style>';
    }
 
 
 
}

AjaxinWP_Theme::get_instance();
