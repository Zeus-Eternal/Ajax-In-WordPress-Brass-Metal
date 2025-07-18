<?php
/**
 * The header for our theme.
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php if (is_singular() && pings_open(get_queried_object())) : ?>
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php endif; ?>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-theme="<?php echo esc_attr(get_theme_mod('ajaxinwp_color_scheme', 'auto')); ?>">
    <?php wp_body_open(); ?>
    
    <?php
    // Get navigation layout, position and style from the Customizer
    $nav_layout   = get_theme_mod( 'ajaxinwp_navigation_layout', 'container' );
    $nav_position = get_theme_mod( 'ajaxinwp_navigation_position', 'position-fixed' );
    $menu_style   = get_theme_mod( 'ajaxinwp_menu_style', 'navbar' );
    ?>
    
    <div class="row">
        <nav class="<?php echo esc_attr($nav_position); ?> navbar navbar-expand-md shadow-sm" aria-label="Main navigation">
            <div class="<?php echo esc_attr($nav_layout); ?>">
                <?php
                // Display the custom logo or the site title as a fallback
                if (has_custom_logo()) {
                    echo '<a class="custom-logo-link" href="' . esc_url(home_url('/')) . '" rel="home">' . 
                            wp_get_attachment_image(get_theme_mod('custom_logo'), 'full', false, array('class' => 'custom-logo')) . 
                         '</a>';
                } else {
                    echo '<a class="navbar-brand homepage-title-link" href="' . esc_url(home_url('/')) . '">' . esc_html(get_bloginfo('name')) . '</a>';
                }
                ?>
                
                <!-- Navbar toggle button for mobile view -->
                <?php if ( 'navbar' === $menu_style ) : ?>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'ajaxinwp' ); ?>">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <?php
                    wp_nav_menu(
                        [
                            'theme_location'  => 'primary',
                            'depth'           => 2,
                            'container'       => 'div',
                            'container_class' => 'collapse navbar-collapse',
                            'container_id'    => 'navbarNavDropdown',
                            'menu_class'      => 'navbar-nav me-auto',
                            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                            'walker'          => new WP_Bootstrap_Navwalker(),
                        ]
                    );
                    ?>
                <?php else : ?>
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#ajaxOffcanvas" aria-controls="ajaxOffcanvas" aria-label="<?php esc_attr_e( 'Toggle navigation', 'ajaxinwp' ); ?>">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="offcanvas <?php echo ( 'offcanvas-right' === $menu_style ) ? 'offcanvas-end' : 'offcanvas-start'; ?>" tabindex="-1" id="ajaxOffcanvas">
                        <div class="offcanvas-header">
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e( 'Close', 'ajaxinwp' ); ?>"></button>
                        </div>
                        <div class="offcanvas-body">
                            <?php
                            wp_nav_menu(
                                [
                                    'theme_location'  => 'primary',
                                    'depth'           => 2,
                                    'container'       => false,
                                    'menu_class'      => 'navbar-nav',
                                    'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                                    'walker'          => new WP_Bootstrap_Navwalker(),
                                ]
                            );
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    </div>
    
    <header id="masthead" class="site-header" role="banner">
        <div id="header-hero-container">
            <?php if (!is_singular() && is_active_sidebar('ajaxinwp_widget_area_header1')) : ?>
                <div class="container-fluid px-0">
                    <?php 
                    // Include the hero header partial template
                    get_template_part('partials/partials-header-hero');
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </header>
