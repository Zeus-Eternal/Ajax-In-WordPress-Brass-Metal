<?php
// Image Options
$wp_customize->add_section('ajaxinwp_image_options', [
    'title'    => __('Image Options', 'ajaxinwp'),
    'priority' => 122,
]);

$wp_customize->add_setting('ajaxinwp_fallback_image', [
    'default'           => '',
    'sanitize_callback' => 'esc_url_raw',
]);

$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'ajaxinwp_fallback_image', [
    'label'       => __('Fallback Image', 'ajaxinwp'),
    'section'     => 'ajaxinwp_image_options',
    'settings'    => 'ajaxinwp_fallback_image',
    'description' => __('Image used when posts lack a featured image.', 'ajaxinwp'),
]));

// Display Featured Image toggle
$wp_customize->add_setting('ajaxinwp_show_featured', [
    'default'           => true,
    'sanitize_callback' => 'wp_validate_boolean',
]);
$wp_customize->add_control('ajaxinwp_show_featured', [
    'label'    => __('Display Featured Image', 'ajaxinwp'),
    'section'  => 'ajaxinwp_image_options',
    'settings' => 'ajaxinwp_show_featured',
    'type'     => 'checkbox',
]);

// Featured image cropping and size
$wp_customize->add_setting('ajaxinwp_feature_crop', [
    'default'           => true,
    'sanitize_callback' => 'wp_validate_boolean',
]);
$wp_customize->add_setting('ajaxinwp_feature_width', [
    'default'           => 1080,
    'sanitize_callback' => 'absint',
]);
$wp_customize->add_setting('ajaxinwp_feature_height', [
    'default'           => 720,
    'sanitize_callback' => 'absint',
]);
$wp_customize->add_control('ajaxinwp_feature_crop', [
    'label'    => __('Hard Crop Featured Image', 'ajaxinwp'),
    'section'  => 'ajaxinwp_image_options',
    'settings' => 'ajaxinwp_feature_crop',
    'type'     => 'checkbox',
]);
$wp_customize->add_control('ajaxinwp_feature_width', [
    'label'       => __('Featured Image Width', 'ajaxinwp'),
    'description' => __('Width in pixels for the featured image size.', 'ajaxinwp'),
    'section'     => 'ajaxinwp_image_options',
    'settings'    => 'ajaxinwp_feature_width',
    'type'        => 'number',
    'input_attrs' => ['min' => 100, 'step' => 1],
]);
$wp_customize->add_control('ajaxinwp_feature_height', [
    'label'       => __('Featured Image Height', 'ajaxinwp'),
    'description' => __('Height in pixels for the featured image size.', 'ajaxinwp'),
    'section'     => 'ajaxinwp_image_options',
    'settings'    => 'ajaxinwp_feature_height',
    'type'        => 'number',
    'input_attrs' => ['min' => 100, 'step' => 1],
]);
?>
