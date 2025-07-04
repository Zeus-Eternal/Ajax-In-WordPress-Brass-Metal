<?php
/**
 * Register widget areas.
 */
class AjaxinWP_Widgets {
    /**
     * Bootstrap hooks.
     */
    public static function init() {
        add_action( 'widgets_init', [ __CLASS__, 'register_widget_areas' ] );
    }

    /**
     * Register sidebars.
     */
    public static function register_widget_areas() {
        // Default sidebar.
        register_sidebar( [
            'id'            => 'sidebar-1',
            'name'          => __( 'Sidebar', 'ajaxinwp' ),
            'description'   => __( 'Main sidebar that appears on the left.', 'ajaxinwp' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ] );

        // Custom widget areas.
        $widget_areas = [ 'Header1', 'Widget1', 'Widget2', 'Widget3', 'Widget4' ];
        foreach ( $widget_areas as $widget_area ) {
            $widget_area_slug = sanitize_title( $widget_area );
            register_sidebar( [
                'id'            => 'ajaxinwp_widget_area_' . $widget_area_slug,
                'name'          => __( ucfirst( $widget_area ), 'ajaxinwp' ),
                'description'   => sprintf( __( 'Custom Widget Area for %s', 'ajaxinwp' ), ucfirst( $widget_area ) ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ] );
        }
    }
}
