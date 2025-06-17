<?php
    // Add Color Scheme Options Section
    $wp_customize->add_section('ajaxinwp_theme_colors', [
        'title'    => __('Theme Colors', 'ajaxinwp'),
        'priority' => 113,
    ]);

    // Add Color Scheme Setting
    $wp_customize->add_setting('ajaxinwp_color_scheme', [
        'default'           => 'auto',
        'transport'         => 'refresh',
        'sanitize_callback' => 'ajaxinwp_sanitize_color_scheme',
    ]);

    $wp_customize->add_control('ajaxinwp_color_scheme', [
        'label'    => __('Default Color Scheme', 'ajaxinwp'),
        'section'  => 'ajaxinwp_theme_colors',
        'settings' => 'ajaxinwp_color_scheme',
        'type'     => 'radio',
        'choices'  => [
            'auto'  => __('Auto', 'ajaxinwp'),
            'color' => __('Color', 'ajaxinwp'),
            'light' => __('Light', 'ajaxinwp'),
            'dark'  => __('Dark', 'ajaxinwp'),
        ],
    ]);
add_action('customize_register', 'ajaxinwp_customize_register');
    // Sanitize the input
    function ajaxinwp_sanitize_color_scheme($input) {
        $valid = ['auto', 'color', 'light', 'dark'];
        return in_array($input, $valid, true) ? $input : 'auto'; // Fallback to auto
    }

    // Add other color settings with defaults and sanitization
    $settings = [
        'ajaxinwp_color_primary' => ['default' => '#0d6efd', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_color_secondary' => ['default' => '#6c757d', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_color_primary_accent' => ['default' => '#ff7f50', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_color_secondary_accent' => ['default' => '#198754', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_link_color' => ['default' => '#0d6efd', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_link_hover_color' => ['default' => '#0a58ca', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_link_decoration' => ['default' => 'none', 'sanitize_callback' => 'sanitize_text_field'],
        'ajaxinwp_link_hover_decoration' => ['default' => 'underline', 'sanitize_callback' => 'sanitize_text_field'],
        'ajaxinwp_nav_bg_color' => ['default' => '#ffc107', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_nav_text_color' => ['default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_nav_link_color' => ['default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_Header1_bg_color' => ['default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_Header1_text_color' => ['default' => '#212529', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_Sidebar1_bg_color' => ['default' => '#212529', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_Sidebar1_text_color' => ['default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_border_color' => ['default' => '#dee2e6', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_button_background_color' => ['default' => '#0d6efd', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_button_text_color' => ['default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_button_hover_color' => ['default' => '#0a58ca', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_body_background_color' => ['default' => '#f8f9fa', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_dark_primary' => ['default' => '#212529', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_dark_secondary' => ['default' => '#343a40', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_dark_accent_primary' => ['default' => '#ffca2c', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_dark_accent_secondary' => ['default' => '#0dcaf0', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_light_primary' => ['default' => '#f8f9fa', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_light_secondary' => ['default' => '#e9ecef', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_light_accent_primary' => ['default' => '#0d6efd', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_light_accent_secondary' => ['default' => '#6610f2', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_admin_primary' => ['default' => '#0d6efd', 'sanitize_callback' => 'sanitize_hex_color'],
        'ajaxinwp_admin_secondary' => ['default' => '#6c757d', 'sanitize_callback' => 'sanitize_hex_color'],
    ];

    foreach ($settings as $setting => $args) {
        $wp_customize->add_setting($setting, array_merge([
            'transport' => 'refresh',
        ], $args));
    }

    foreach ($settings as $setting => $args) {
        $control_args = [
            'label'    => __(ucwords(str_replace('_', ' ', str_replace('ajaxinwp_', '', $setting))), 'ajaxinwp'),
            'section'  => 'ajaxinwp_theme_colors',
            'settings' => $setting,
        ];

        if ($args['sanitize_callback'] === 'sanitize_hex_color') {
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $setting, $control_args));
        } elseif ($args['sanitize_callback'] === 'sanitize_text_field' && ($setting === 'ajaxinwp_link_decoration' || $setting === 'ajaxinwp_link_hover_decoration')) {
            $control_args['type'] = 'select';
            $control_args['choices'] = [
                'none' => __('None', 'ajaxinwp'),
                'underline' => __('Underline', 'ajaxinwp'),
                'overline' => __('Overline', 'ajaxinwp'),
                'line-through' => __('Line-through', 'ajaxinwp'),
            ];
            $wp_customize->add_control($setting, $control_args);
        } else {
            $control_args['type'] = 'text';
            $wp_customize->add_control($setting, $control_args);
        }
    }

    // Separator for organization
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'ajaxinwp_color_separator', [
        'type' => 'hidden',
        'section' => 'ajaxinwp_theme_colors',
        'settings' => [],
    ]));