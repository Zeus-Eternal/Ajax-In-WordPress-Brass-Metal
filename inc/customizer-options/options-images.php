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
    'label'    => __('Fallback Image', 'ajaxinwp'),
    'section'  => 'ajaxinwp_image_options',
    'settings' => 'ajaxinwp_fallback_image',
    'description' => __('Image used when posts lack a featured image.', 'ajaxinwp'),
]));
?>
