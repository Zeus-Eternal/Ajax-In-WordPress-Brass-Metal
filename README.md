# AjaxInWP Brass-Metal Theme

## Overview
AjaxInWP Brass-Metal is a fully styled WordPress theme designed to provide a rich user experience with advanced features. It leverages Ajax for dynamic content loading, ensuring smooth transitions and an enhanced user experience. Ideal for both developers looking for a robust starting point and end-users who want a ready-to-use solution.
**Version 1.5.5**

## Features
- **Ajax-Powered**: Dynamic content loading without page refreshes.
- **Fully Styled**: Ready-to-use with beautiful design elements.
- **Developer-Friendly**: Easy to customize and extend.
- **Theme Modes**: Supports Dark, Light, and Color modes for different visual preferences.
- **Style Variations**: Choose from default, light, or dark designs in the editor.
- **Block Theme Ready**: Includes `theme.json` for full site editing support.
- **Multiple Block Templates**: Templates for posts, pages, archives, and 404 screens.
- **Block Patterns**: Reusable layout patterns registered via PHP.
- **Local Assets**: Bootstrap and Font Awesome are bundled locally for privacy.
- **Image Fallbacks**: Missing images automatically display a default placeholder.
- **Customizable Fallback Image**: Set your own placeholder image in the Customizer.
- **Featured Image Controls**: Toggle featured images and define crop size directly in the Customizer.
- **Auto Dark/Light Mode**: Choose "Auto" to follow your device's color preference.
 
 

- **Admin Color Integration**: Customize the admin palette to match your brand.
 
- **Resilient Image Handling**: Broken images are replaced client-side for a polished look.


- **Flexible Navigation**: Multiple menu locations and new offcanvas or navbar menu styles.
- **Automatic Table of Contents**: Posts include a generated index for easy navigation.
- **OOP Architecture**: Core features are encapsulated in the `AjaxinWP_Theme` class for cleaner code.
## Why Ajax?

Ajax (Asynchronous JavaScript and XML) is a powerful web development technique that allows for the asynchronous loading and updating of content without requiring a full page refresh. This technology enhances the user experience by making web applications faster, more responsive, and interactive. 

### Development Benefits of Ajax

1. **Improved User Experience**:
   - **Seamless Interactions**: Ajax enables seamless interactions by updating parts of a web page without reloading the entire page, providing a smoother and more dynamic user experience.
   - **Faster Load Times**: By fetching only the necessary data, Ajax reduces the amount of data transferred between the client and server, resulting in faster load times.

2. **Reduced Server Load**:
   - **Efficient Data Handling**: Ajax allows for efficient data handling by sending and receiving only the data that is needed, reducing the overall server load and improving performance.

3. **Enhanced Interactivity**:
   - **Dynamic Content**: With Ajax, developers can create highly interactive web applications that can update content dynamically based on user actions without disrupting the user's flow.
   - **Real-Time Updates**: Ajax is ideal for applications that require real-time updates, such as live chat applications, notifications, and dashboards.

4. **Scalability and Flexibility**:
   - **Modular Development**: Ajax promotes modular development by allowing developers to build individual components that can be updated independently, making the codebase more manageable and scalable.
   - **Compatibility**: Ajax works well with various web technologies and frameworks, making it a versatile choice for a wide range of applications.

5. **Enhanced SEO**:
   - **Progressive Enhancement**: When used correctly, Ajax can improve SEO by providing better user engagement metrics and allowing search engines to index dynamic content.

6. **Improved Performance**:
   - **Bandwidth Efficiency**: By loading only the necessary parts of a page, Ajax minimizes bandwidth usage, making web applications more efficient and cost-effective.

### Use Cases of Ajax

- **E-commerce Sites**: Ajax can be used to update shopping cart contents, product details, and customer reviews without refreshing the page, enhancing the shopping experience.
- **Social Media Platforms**: Real-time updates for feeds, notifications, and messages are made possible with Ajax, providing a smooth and interactive user experience.
- **Content Management Systems**: Ajax allows for inline editing of content and real-time updates, making content management more efficient and user-friendly.
- **Single Page Applications (SPAs)**: Ajax is a fundamental technology for SPAs, enabling dynamic content updates and a seamless user experience.
- **Dashboards and Analytics Tools**: Real-time data visualization and updates are crucial for dashboards, and Ajax provides the necessary functionality to achieve this.

By leveraging Ajax, developers can create more engaging, efficient, and interactive web applications, leading to better user satisfaction and overall performance.

## Installation
1. **Download the Theme**: Clone or download the theme from the [GitHub repository](#).
2. **Upload to WordPress**:
   - Go to `Appearance > Themes > Add New > Upload Theme`.
   - Choose the downloaded zip file and click `Install Now`.
3. **Activate the Theme**: After installation, click `Activate` to start using AjaxInWP Brass-Metal.

## Usage
1. **Customize the Theme**: Utilize the WordPress Customizer and `theme.json` to add your own styles and configurations.
2. **Implement Ajax**: Follow the included documentation to implement Ajax-based content loading for your site.

## Asset Bundling
All third-party CSS and JavaScript libraries are bundled in the `assets/` directory. This keeps your site independent of external CDNs and allows offline development. Update these files as needed, then run `wp_enqueue_style` and `wp_enqueue_script` versioning via `filemtime()` ensures browsers get the latest versions.

## Block Theme Support
 
AjaxInWP includes a core `theme.json` file and three style variations.
Check the Styles panel in the Site Editor to switch between Color, Light, or Dark looks.



 


## Block Patterns
Premium block patterns help you build pages faster. All patterns are registered under the **AjaxInWP** category and include the following examples:

1. **Hero Section**
2. **Features Grid**
3. **About Section**
4. **Services Overview**
5. **Pricing Tables**
6. **Testimonials**
7. **Team Members**
8. **Image Gallery**
9. **Call to Action**
10. **Newsletter Signup**
11. **FAQ List**
12. **Contact Section**
13. **Posts Grid**
14. **Banner Header**
15. **Footer Call to Action**
16. **Q&A Accordion**
17. **Product Grid**
18. **Admin Dashboard**

Insert any of these patterns from the block inserter to quickly compose rich layouts.

## Table of Contents
Each post automatically displays a table of contents based on its headings. This helps readers navigate long articles more easily.

 


 
## Ajax Implementation
AjaxInWP Brass-Metal uses JavaScript to handle internal link clicks and fetch content dynamically. Below is a brief overview of how it works:

### JavaScript Example
```javascript
class AjaxinWP {
    constructor() {
        this.initialize();
    }

    initialize() {
        document.addEventListener('click', event => {
            const target = event.target.closest('a');
            if (target && this.isInternalLink(target)) {
                event.preventDefault();
                this.loadContent(target.href);
            }
        });

        window.addEventListener('popstate', () => {
            this.loadContent(window.location.href, false);
        });
    }

    isInternalLink(link) {
        return link.hostname === window.location.hostname && !link.hasAttribute('target');
    }

    getAjaxContainer() {
        return document.querySelector('#ajax-container');
    }

    showLoader() {
        const loader = `<div id="loader" class="loader-overlay"><div class="spinner"></div></div>`;
        document.body.insertAdjacentHTML('beforeend', loader);
    }

    hideLoader() {
        const loader = document.getElementById('loader');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => loader.remove(), 500);
        }
    }

    loadContent(url, updateHistory = true) {
        const container = this.getAjaxContainer();
        if (!container) return;

        container.style.opacity = '0';
        this.showLoader();

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, "text/html");
                const ajaxContainerContent = doc.querySelector('#ajax-container');
                if (ajaxContainerContent) {
                    setTimeout(() => {
                        container.innerHTML = ajaxContainerContent.innerHTML;
                        container.style.opacity = '1';
                    }, 500);
                }
                if (updateHistory) {
                    window.history.pushState({}, '', url);
                }
            })
            .catch(() => {
                container.innerHTML = '<div class="alert alert-danger">Error loading content.</div>';
- **Default Color Scheme**: Choose between 'Auto', 'Color', 'Light', or 'Dark'.
  - *Auto* follows your device preference for dark or light mode.
            })
            .finally(() => {
                this.hideLoader();
            });
    }
}

// Initialize the AjaxinWP class
document.addEventListener('DOMContentLoaded', () => {
    new AjaxinWP();
});
```

# AjaxInWP Theme Customizer Documentation

## Overview

AjaxInWP Theme Customizer offers a comprehensive suite of customization options to tailor the appearance and functionality of your WordPress site. This guide provides detailed instructions on configuring the theme using the built-in WordPress Customizer, including settings for colors, typography, layout, and widgets.

## Customizer Sections and Settings

### Theme Colors

**Section**: `ajaxinwp_theme_colors`

This section allows you to customize the color scheme of your theme.

- **Default Color Scheme**: Choose between 'Color', 'Light', or 'Dark'.
- *All color options start with an award-winning palette so your site looks great from the start.*
- **Primary Color**: Set the primary color used throughout the theme.
- **Secondary Color**: Set the secondary color used throughout the theme.
- **Primary Accent Color**: Set the primary accent color.
- **Secondary Accent Color**: Set the secondary accent color.
- **Link Color**: Set the default link color.
- **Link Hover Color**: Set the color for links when hovered over.
- **Link Decoration**: Choose the decoration style for links (e.g., None, Underline, Overline, Line-through).
- **Link Hover Decoration**: Choose the decoration style for links when hovered over.
- **Navigation Background Color**: Set the background color for the navigation bar.
- **Navigation Text Color**: Set the text color for the navigation bar.
- **Navigation Link Color**: Set the link color for the navigation bar.
- **Header Background Color**: Set the background color for the header.
- **Header Text Color**: Set the text color for the header.
- **Sidebar Background Color**: Set the background color for the sidebar.
- **Sidebar Text Color**: Set the text color for the sidebar.
- **Border Color**: Set the border color used throughout the theme.
- **Button Background Color**: Set the background color for buttons.
- **Button Text Color**: Set the text color for buttons.
- **Button Hover Color**: Set the color for buttons when hovered over.
- **Body Background Color**: Set the background color for the body.
- **Dark Primary Color**: Set the primary color for dark mode.
- **Dark Secondary Color**: Set the secondary color for dark mode.
- **Dark Accent Primary Color**: Set the primary accent color for dark mode.
- **Dark Accent Secondary Color**: Set the secondary accent color for dark mode.
- **Light Primary Color**: Set the primary color for light mode.
- **Light Secondary Color**: Set the secondary color for light mode.
- **Light Accent Primary Color**: Set the primary accent color for light mode.
- **Light Accent Secondary Color**: Set the secondary accent color for light mode.

### Layout Options

**Section**: `ajaxinwp_layout_options`

This section allows you to customize the layout of various elements of your theme.

- **Navigation Position**: Choose between 'Fixed' or 'Static' for the navigation bar.
- **Navigation Layout**: Choose between 'Default', 'Container', or 'Container Fluid' for the navigation layout.
- **Header Layout**: Choose between 'Default', 'Container', or 'Container Fluid' for the header layout.
### Image Options

**Section**: `ajaxinwp_image_options`

This section controls fallback images used by the theme.

- **Fallback Image**: Upload an image to display whenever posts have no featured image.

- **Widget Layout**: Choose between 'Default', 'Container', or 'Container Fluid' for the widget layout.
- **Footer Layout**: Choose between 'Default

', 'None', or 'Show' for the footer layout.
- **Content Layout**: Choose between 'Right Sidebar', 'Left Sidebar', or 'No Sidebar' for the content layout.

### Typography Options

**Section**: `ajaxinwp_typography_options`

This section allows you to customize the typography of your theme.

- **Primary Font**: Select a primary font from a list of popular web fonts.
- **Primary Font Color**: Set the color for the primary font.
- **Primary Font Weight**: Choose the weight for the primary font (e.g., Normal, Bold, 100, 200).
- **Heading Font**: Select a font for headings from a list of popular web fonts.
- **Heading Font Color**: Set the color for heading fonts.
- **Heading Font Weight**: Choose the weight for heading fonts (e.g., Normal, Bold, 100, 200).
- **Link Color**: Set the color for links.
- **Link Weight**: Choose the weight for links (e.g., Normal, Bold).
- **Link Decoration**: Choose the decoration style for links (e.g., None, Underline).
- **Link Hover Color**: Set the color for links when hovered over.
- **Link Hover Weight**: Choose the weight for links when hovered over (e.g., Normal, Bold).
- **Link Hover Decoration**: Choose the decoration style for links when hovered over (e.g., None, Underline).
- **Global Font Size**: Set the global font size for the theme (e.g., 12px, 14px, 16px, 18px, 20px).
- **Navigation Font**: Select a custom font for your navigation menus.
- **Navigation Font Weight**: Choose the weight for navigation links.

### Widgets

**Section**: `ajaxinwp_widgets`

This section allows you to manage widget areas and customize their appearance.

- **Widget Areas**: Enter widget area names separated by new lines. The default widget areas are 'Header1', 'Widget1', 'Widget2', 'Widget3', 'Widget4', and 'Sidebar1'.
- **Widget Name**: Customize the name of each widget area.
- **Widget Background Color**: Set the background color for each widget area.
- **Widget Icon**: Select an icon for each widget area from a pre-defined list of icons.
- **Custom Icon Class**: Enter a custom icon class if the 'Custom Icon' option is selected.
- **Widget Description**: Add a description for each widget area.
- **Show Icon**: Toggle the display of icons for each widget area.
- **Show Title**: Toggle the display of titles for each widget area.
- **Show Description**: Toggle the display of descriptions for each widget area.

## Sanitization Functions

To ensure that the input values are valid and safe, various sanitization functions are used in the customizer:

- **ajaxinwp_sanitize_color_scheme**: Sanitizes the color scheme selection.
- **ajaxinwp_sanitize_navigation_position**: Sanitizes the navigation position selection.
- **ajaxinwp_sanitize_navigation_layout**: Sanitizes the navigation layout selection.
- **ajaxinwp_sanitize_header_layout**: Sanitizes the header layout selection.
- **ajaxinwp_sanitize_widget_layout**: Sanitizes the widget layout selection.
- **ajaxinwp_sanitize_footer_layout**: Sanitizes the footer layout selection.
- **ajaxinwp_sanitize_content_layout**: Sanitizes the content layout selection.
- **sanitize_hex_color**: Sanitizes hex color values.
- **sanitize_text_field**: Sanitizes text fields.
- **wp_validate_boolean**: Validates boolean values.

## Adding Customizer Settings and Controls

To add new settings and controls to the customizer, you can use the following pattern:

1. **Add a Section**: 
    ```php
    $wp_customize->add_section('section_id', [
        'title'    => __('Section Title', 'textdomain'),
        'priority' => 130,
    ]);
    ```

2. **Add a Setting**:
    ```php
    $wp_customize->add_setting('setting_id', [
        'default'           => 'default_value',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_function',
    ]);
    ```

3. **Add a Control**:
    ```php
    $wp_customize->add_control('setting_id', [
        'label'    => __('Control Label', 'textdomain'),
        'section'  => 'section_id',
        'settings' => 'setting_id',
        'type'     => 'control_type',
        'choices'  => [ /* choices for select, radio, or checkbox controls */ ],
    ]);
    ```

By following these patterns, you can expand the customizer options to suit your theme's requirements. For more advanced customization, refer to the WordPress Codex or the Theme Customization API documentation.

## Translations
Translation templates reside in the `languages/` directory. Generate updated `.po` files with tools like `xgettext` or the WordPress CLI and save compiled `.mo` files in the same folder so WordPress can load them automatically.

## Contributions
We welcome contributions from the community. Please submit issues and pull requests to the [GitHub repository](https://github.com/agustealo/Ajax-In-WordPress-Brass-Metal).

## License
This project is licensed under the GNU General Public License version 3 or later. See the [LICENSE](https://github.com/agustealo/Ajax-In-WordPress-Brass-Metal/blob/main/LICENSE) file for details.
