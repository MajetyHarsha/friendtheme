## Moove Theme: Key Features and Extensibility

This section delves into specific features of the Moove theme, highlighting its extensive front page customization options, built-in accessibility enhancements, integration capabilities with external services, and its extensibility through Moodle's plugin callback system.

### 1. Front Page Customization Capabilities

The Moove theme offers a highly configurable front page, allowing administrators to create a unique and engaging landing experience directly through theme settings. The structure is defined in `templates/frontpage.mustache` and populated based on configurations in `settings.php`.

*   **Slideshow (Slider)**:
    *   Controlled by settings like `slideronoff` (to enable/disable) and `slidercount` (number of slides, typically up to 5).
    *   Each slide can be configured with an image (`sliderimage1`, `sliderimage2`, ...), a heading (`sliderheading1`, ...), content/caption (`slidercontent1`, ...), and a call-to-action button with text (`sliderbuttontext1`, ...) and URL (`sliderbuttonurl1`, ...).
    *   The `templates/frontpage.mustache` file uses these settings to conditionally render the slider and loop through the configured slides.

*   **Marketing Boxes**:
    *   Enabled by the `displaymarketingbox` setting.
    *   Administrators can configure multiple marketing boxes (e.g., `marketingboxcount` might define the number).
    *   Each box can have a heading (`marketingheading1`, ...), content (`marketingcontent1`, ...), an icon (e.g., `marketingicon1`, ..., chosen from a predefined list or Font Awesome), and a button with text (`marketingbuttontext1`, ...) and URL (`marketingbuttonurl1`, ...).
    *   These are rendered in `templates/frontpage.mustache` if enabled, providing flexible content blocks for highlighting key information or links.

*   **"Numbers" Section**:
    *   Controlled by the `numbersfrontpage` setting to enable/disable its display.
    *   The content for this section, which typically showcases key statistics or achievements in a visually appealing "numbers" format, is managed via `numbersfrontpagecontent`. This setting might allow for a structured input (e.g., using HTML or a specific format) to define each "number" item, its title, and associated icon.
    *   `templates/frontpage.mustache` includes this section if the `numbersfrontpage` setting is active.

*   **FAQs (Frequently Asked Questions)**:
    *   Enabled by the `displayfaq` setting.
    *   The number of FAQs can be set (e.g., `faqcount`).
    *   Each FAQ item consists of a question (`faqquestion1`, `faqquestion2`, ...) and its corresponding answer (`faqanswer1`, `faqanswer2`, ...).
    *   `templates/frontpage.mustache` renders this section, often as an accordion or list, if enabled through theme settings.

These sections, along with others like "About Us" (`aboutusfrontpage`, `aboutusfrontpagecontent`), "Video Section" (`videofrontpage`, `videofrontpagecontent`), and "Course Categories" (`coursecategoriesfrontpage`), allow administrators to build a comprehensive and informative front page tailored to their institution's needs directly from the theme settings interface, without needing to write any code.

### 2. Accessibility Features

Moove incorporates several features aimed at improving usability for users with disabilities, adhering to web accessibility guidelines.

*   **Accessibility Bar/Settings**:
    *   The theme often includes an accessibility bar or a settings panel, which can be triggered from the user menu (as seen in `templates/core/user_menu.mustache` which might initialize `theme_moove/accessibilitysettings`).
    *   `core_renderer.php` contributes to this by potentially adding a body class like `hasaccessibilitybar` if the feature is enabled.
    *   User preferences for accessibility, such as `accessibilitystyles_fontsizeclass` (for adjusting font size) and `accessibilitystyles_sitecolorclass` (for changing color contrast schemes), are respected and applied. These preferences are typically stored as user profile settings and used by the theme to modify CSS classes on the body or main elements, dynamically altering the site's appearance.

*   **OpenDyslexic Font**:
    *   The theme includes the OpenDyslexic font in its `fonts/` directory.
    *   This font is specifically designed to increase readability for users with dyslexia.
    *   Theme settings (e.g., a dropdown in the accessibility settings or a general theme setting) likely allow users or administrators to select this font, which would then be applied through SCSS rules (e.g., updating the `$font-family-base` variable or adding specific CSS classes).

*   **Purpose**: These features collectively aim to make the Moodle site more perceivable, operable, understandable, and robust for all users, especially those with visual impairments, reading difficulties, or other disabilities. This includes providing sufficient color contrast, adjustable text sizes, and readable fonts.

### 3. Integrations

Moove provides built-in support for common third-party services and Moodle features.

*   **Google Analytics**:
    *   The `standard_head_html()` method in `classes/output/core_renderer.php` includes logic to inject the Google Analytics tracking script into the `<head>` of every page.
    *   This is conditional upon a Google Analytics Tracking ID being provided in the theme settings (e.g., `$theme->settings->googleanalytics` or a similarly named setting like `gaid`). If the ID is present, the renderer outputs the necessary JavaScript code to enable tracking of site usage statistics.

*   **H5P Custom Styling**:
    *   Moove allows for custom styling of H5P interactive content.
    *   A setting, `theme_moove/hvpcss` (defined in `settings.php`), allows administrators to input custom CSS rules specifically for H5P content.
    *   The `lib.php` file contains a function, `theme_moove_serve_hvp_css()`, which is responsible for taking the CSS from this setting and making it available to H5P content. This ensures that H5P activities can be styled to match the overall look and feel of the theme.

### 4. Extensibility through Callbacks

The Moove theme is designed to be extensible by other Moodle plugins, allowing them to integrate their functionalities seamlessly into the theme's structure.

*   **Plugin Hook Usage**: `core_renderer.php` (and potentially other renderer or layout files) utilizes Moodle's plugin hook system. Specifically, it uses functions like:
    *   `get_plugins_with_function('moove_additional_header', array('theme' => $this->page->theme->name))`
    *   `get_plugins_with_function('moove_module_footer', array('coursemodule' => $this->page->cm))` (or similar context-aware parameters).

*   **Mechanism**:
    *   Other Moodle plugins (e.g., blocks, activities, local plugins) can implement callback functions with these specific names (e.g., `local_myplugin_moove_additional_header()`).
    *   When Moove renders a page section containing these hooks (like the header or the footer of a course module), it calls `get_plugins_with_function()`. Moodle then searches all installed and enabled plugins for any that define the specified function.
    *   If such functions are found, Moodle executes them, and their output is injected into the Moove theme at that point.
*   **Purpose**: This system allows other plugins to add custom HTML, CSS, or JavaScript to specific parts of the Moove theme layout without modifying Moove's core code. For example, a plugin could add extra navigation elements to the header, or custom information to the footer of course modules, ensuring better integration and a consistent user experience. This promotes a modular and maintainable Moodle ecosystem.

These features demonstrate Moove's flexibility in terms of content presentation, user support, and integration with the broader Moodle plugin environment.
