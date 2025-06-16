<?php
/**
 * Generate dynamic CSS based on customizer settings.
 */
class AjaxinWP_CSS_Generator {
    /**
     * Hook into WordPress.
     */
    public static function init() {
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_styles' ] );
    }

    /**
     * Enqueue styles and inline custom CSS.
     */
    public static function enqueue_styles() {
        wp_enqueue_style( 'ajaxinwp-general-style', get_template_directory_uri() . '/assets/css/variables.css', [], wp_get_theme()->get( 'Version' ) );
        wp_enqueue_style( 'ajaxinwp-theme-style', get_template_directory_uri() . '/assets/css/theme.css', [], wp_get_theme()->get( 'Version' ) );
        wp_add_inline_style( 'ajaxinwp-theme-style', self::customizer_css() );
    }

    /**
     * Build the customizer CSS.
     */
    private static function customizer_css() {
        $darken = 30;

        $default_colors = [
            '--primary-color'         => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_color_primary', '#dee2e6' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_primary', '#a2bfc1' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_primary', '#212529' ) ),
            ],
            '--secondary-color'       => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_color_secondary', '#212529' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_secondary', '#161b22' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_secondary', '#e1eaf3' ) ),
            ],
            '--primary-accent-color'  => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_color_primary_accent', '#ffc1ea' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_accent_primary', '#bb86fc' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_accent_primary', '#007bff' ) ),
            ],
            '--secondary-accent-color'=> [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_color_secondary_accent', '#7551f7' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_accent_secondary', '#beb4f7' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_accent_secondary', '#0056b3' ) ),
            ],
            '--primary-font'          => [
                'color' => sanitize_text_field( get_theme_mod( 'ajaxinwp_primary_font', 'Roboto, sans-serif' ) ),
            ],
            '--heading-color'         => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_heading_color', '#212529' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_accent_secondary', '#beb4f7' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_accent_secondary', '#0056b3' ) ),
            ],
            '--secondary-heading-color' => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_secondary_heading_color', '#ffffff' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_secondary', '#cccccc' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_secondary', '#ffffff' ) ),
            ],
            '--nav-bg-color'          => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_nav_bg_color', '#f8f9fa' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_secondary', '#161b22' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_secondary', '#ffffff' ) ),
            ],
            '--nav-text-color'        => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_nav_text_color', '#212529' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_primary', '#a2bfc1' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_primary', '#ffffff' ) ),
            ],
            '--nav-link-color'        => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_nav_link_color', '#007bff' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_primary', '#a2bfc1' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_primary', '#ffffff' ) ),
            ],
            '--nav-link-hover-color'  => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_nav_link_hover_color', '#0056b3' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_accent_secondary', '#beb4f7' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_accent_secondary', '#0056b3' ) ),
            ],
            '--nav-font-family'       => [
                'color' => sanitize_text_field( get_theme_mod( 'ajaxinwp_nav_font', 'Roboto, sans-serif' ) ),
            ],
            '--nav-font-weight'       => [
                'color' => sanitize_text_field( get_theme_mod( 'ajaxinwp_nav_font_weight', 'normal' ) ),
            ],
            '--header-bg-color'       => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_Header1_bg_color', '#f8f9fa' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_secondary', '#161b22' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_secondary', '#ffffff' ) ),
            ],
            '--header-text-color'     => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_Header1_text_color', '#212529' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_secondary', '#e1eaf3' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_secondary', '#212529' ) ),
            ],
            '--header-icon'           => [
                'color' => sanitize_text_field( get_theme_mod( 'ajaxinwp_Header1_icon', '&#128101;' ) ),
                'dark'  => '&#128101;',
                'light' => '&#128101;',
            ],
            '--sidebar-bg-color'      => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_Sidebar1_bg_color', '#212529' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_secondary', '#161b22' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_secondary', '#ffffff' ) ),
            ],
            '--sidebar-text-color'    => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_Sidebar1_text_color', '#ffffff' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_secondary', '#e1eaf3' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_secondary', '#212529' ) ),
            ],
            '--sidebar-icon'          => [
                'color' => sanitize_text_field( get_theme_mod( 'ajaxinwp_Sidebar1_icon', '&#128101;' ) ),
                'dark'  => '&#128101;',
                'light' => '&#128101;',
            ],
            '--link-color'            => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_link_color', '#007bff' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_accent_primary', '#7ab7ff' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_accent_primary', '#007bff' ) ),
            ],
            '--link-hover-color'      => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_link_hover_color', '#0056b3' ) ),
                'dark'  => self::darken_color( sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_accent_primary', '#7ab7ff' ) ), $darken ),
                'light' => self::darken_color( sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_accent_primary', '#007bff' ) ), $darken ),
            ],
            '--link-decoration'       => [
                'color' => sanitize_text_field( get_theme_mod( 'ajaxinwp_link_decoration', 'none' ) ),
                'dark'  => 'none',
                'light' => 'none',
            ],
            '--link-hover-decoration' => [
                'color' => sanitize_text_field( get_theme_mod( 'ajaxinwp_link_hover_decoration', 'underline' ) ),
                'dark'  => 'underline',
                'light' => 'underline',
            ],
            '--button-bg-color'       => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_button_background_color', '#007bff' ) ),
                'dark'  => self::darken_color( sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_secondary', '#161b22' ) ), $darken ),
                'light' => self::darken_color( sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_secondary', '#ffffff' ) ), $darken ),
            ],
            '--button-text-color'     => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_button_text_color', '#ffffff' ) ),
                'dark'  => sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_secondary', '#a2bfc1' ) ),
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_secondary', '#ffffff' ) ),
            ],
            '--button-hover-color'    => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_button_hover_color', '#0056b3' ) ),
                'dark'  => self::darken_color( sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_accent_primary', '#7ab7ff' ) ), $darken ),
                'light' => self::darken_color( sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_accent_primary', '#007bff' ) ), $darken ),
            ],
            '--border-color'          => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_border_color', '#dee2e6' ) ),
                'dark'  => 'hsla(0,0%,100%,.2)',
                'light' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_primary', '#dee2e6' ) ),
            ],
            '--body-bg-color'         => [
                'color' => sanitize_hex_color( get_theme_mod( 'ajaxinwp_body_background_color', '#e81b85' ) ),
                'dark'  => self::darken_color( sanitize_hex_color( get_theme_mod( 'ajaxinwp_dark_secondary', '#161b22' ) ), $darken ),
                'light' => self::darken_color( sanitize_hex_color( get_theme_mod( 'ajaxinwp_light_secondary', '#ffffff' ) ), $darken ),
            ],
        ];

        $logo_desktop_dark       = get_theme_mod( 'ajaxinwp_logo_dark' );
        $logo_desktop_light      = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
        $logo_desktop_light_url  = is_array( $logo_desktop_light ) ? $logo_desktop_light[0] : '';
        $logo_size_desktop       = get_theme_mod( 'ajaxinwp_logo_size', 160 );
        $logo_size_tablet        = get_theme_mod( 'ajaxinwp_logo_size_tablet', 120 );
        $logo_size_mobile        = get_theme_mod( 'ajaxinwp_logo_size_mobile', 80 );

        $common_variables = [];
        $dark_variables   = [];
        $light_variables  = [];
        foreach ( $default_colors as $css_var => $value ) {
            $common_variables[ $css_var ] = $value;
            if ( isset( $value['dark'] ) ) {
                $dark_variables[ $css_var ] = $value;
            }
            if ( isset( $value['light'] ) ) {
                $light_variables[ $css_var ] = $value;
            }
        }

        $css  = '<style type="text/css">';
        $css .= self::generate_css_variables( $common_variables );
        $css .= self::generate_theme_css( 'dark', $dark_variables );
        $css .= self::generate_theme_css( 'light', $light_variables );
        $css .= self::generate_theme_css( 'color', [] );
        $css .= '.custom-logo-link img{max-width:' . intval( $logo_size_desktop ) . 'px;height:auto;}';
        $css .= '@media (max-width:767.98px){.custom-logo-link img{max-width:' . intval( $logo_size_mobile ) . 'px;}}';
        $css .= '@media (min-width:768px) and (max-width:991.98px){.custom-logo-link img{max-width:' . intval( $logo_size_tablet ) . 'px;}}';
        if ( $logo_desktop_light_url ) {
            $css .= 'body[data-theme="light"] .custom-logo-link img{content:url(' . esc_url( $logo_desktop_light_url ) . ');}';
            $css .= 'body[data-theme="color"] .custom-logo-link img{content:url(' . esc_url( $logo_desktop_light_url ) . ');}';
        }
        if ( $logo_desktop_dark ) {
            $css .= 'body[data-theme="dark"] .custom-logo-link img{content:url(' . esc_url( $logo_desktop_dark ) . ');}';
        }
        $css .= '</style>';
        return $css;
    }

    /**
     * Darken a hex color.
     */
    private static function darken_color( $color, $percent ) {
        $color = str_replace( '#', '', $color );
        $r     = hexdec( substr( $color, 0, 2 ) );
        $g     = hexdec( substr( $color, 2, 2 ) );
        $b     = hexdec( substr( $color, 4, 2 ) );
        $r     = max( 0, $r - round( 255 * $percent / 100 ) );
        $g     = max( 0, $g - round( 255 * $percent / 100 ) );
        $b     = max( 0, $b - round( 255 * $percent / 100 ) );
        return sprintf( '#%02x%02x%02x', $r, $g, $b );
    }

    /**
     * Generate CSS variables.
     */
    private static function generate_css_variables( $variables ) {
        $css = ':root{';
        foreach ( $variables as $css_var => $value ) {
            $css .= $css_var . ':' . $value['color'] . ';';
        }
        $css .= '}';
        return $css;
    }

    /**
     * Generate theme specific CSS.
     */
    private static function generate_theme_css( $theme, $variables ) {
        $css = '';
        if ( ! empty( $variables ) ) {
            $css .= 'body[data-theme="' . $theme . '"]{';
            foreach ( $variables as $css_var => $value ) {
                if ( isset( $value[ $theme ] ) ) {
                    $css .= $css_var . ':' . $value[ $theme ] . ';';
                }
            }
            $css .= '}';
        }
        return $css;
    }
}
