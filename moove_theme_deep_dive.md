## Moove Theme: A Deep Dive into its Inner Workings

This document provides a detailed look into the configuration, PHP architecture, styling mechanisms, templating system, and JavaScript interactivity of the Moove theme.

### 1. Configuration Management

The Moove theme's configuration is managed through a combination of PHP files that define its core properties and user-configurable settings.

*   **`config.php`**: This is the foundational configuration file for the theme. Its primary roles include:
    *   **Basic Theme Definition**: It sets the theme's internal name (e.g., `theme_moove`), which Moodle uses to identify it.
    *   **Layouts**: It defines the available page layouts that the theme supports (e.g., `frontpage`, `standard`, `login`).
    *   **Parent Theme**: It specifies the parent theme from which Moove inherits. In this case, it's typically Moodle's core "Boost" theme, allowing Moove to leverage and extend Boost's functionalities.
    *   **SCSS Callback**: It often declares a PHP function (e.g., `theme_moove_get_main_scss_content` from `lib.php`) that Moodle calls to get the theme's SCSS content. This is crucial for dynamic styling.

*   **`settings.php`**: This file defines the extensive array of settings that administrators can configure through the Moodle User Interface (UI) under theme settings. These settings allow for significant customization without direct code modification. Examples include:
    *   **Branding**: Uploading a custom logo, favicon.
    *   **Color Scheme**: Selecting primary, secondary, and other key colors for the theme.
    *   **Fonts**: Choosing font families and sizes.
    *   **Frontpage Elements**: Configuring the visibility and content of various frontpage sections (like sliders, marketing spots, course carousels).
    *   **Layout Options**: Settings related to page width, sidebar presence, etc.
    *   **Custom CSS/JS**: Fields to add custom CSS or JavaScript code.

*   **`lib.php` (SCSS related functions)**: This file contains vital PHP functions that bridge the gap between the UI settings and the theme's actual styling. Key functions involved in dynamic SCSS generation include:
    *   **`theme_moove_get_main_scss_content($theme)`**: This function is typically registered in `config.php`. It aggregates all necessary SCSS, including base styles, user-defined settings (like colors and fonts), and component styles, into a single block of SCSS code that Moodle then compiles.
    *   **`theme_moove_get_pre_scss($theme)` (or similar)**: Often used to inject SCSS variable definitions based on the settings saved in `settings.php`. For instance, if an admin picks a primary color from the settings UI, this function would generate the corresponding SCSS variable declaration (e.g., `$primary-color: #RRGGBB;`).
    *   **`theme_moove_get_extra_scss($theme)` (or similar)**: Can be used to append additional SCSS rules, perhaps from a custom SCSS input field in the theme settings, or to include styles for specific plugins or configurations.

    These functions ensure that the theme's appearance reflects the administrator's choices by dynamically constructing the SCSS code before it's compiled into static CSS.

### 2. PHP Classes (`classes/` directory)

The `classes/` directory houses PHP classes that extend Moodle's core functionalities, primarily for customizing HTML output and encapsulating theme-specific logic.

*   **Renderers (e.g., `classes/output/core_renderer.php`)**:
    *   **Purpose**: Renderers are responsible for generating the HTML output for various Moodle components. Moove overrides and extends base Moodle renderers (like `core_renderer` or renderers for specific activities/blocks) to tailor the markup to its design and functional requirements.
    *   **Customization**: By creating its own renderer class (e.g., `theme_moove_core_renderer` that extends a Moodle core renderer), Moove can:
        *   Change the HTML structure of elements like the user menu, login form, navigation bar, course listings, etc.
        *   Add custom CSS classes for styling.
        *   Inject new elements or modify existing ones.
        *   For example, `theme_moove_core_renderer` might have methods like `render_user_menu` or `render_login_info` that output HTML specific to Moove's header and user navigation design.

*   **Utility Classes (e.g., `classes/util/course.php`)**:
    *   **Role**: Utility classes encapsulate specific logic and provide helper functions that are used across different parts of the theme. This promotes code reusability and better organization.
    *   **Functionalities**: Examples of what `classes/util/course.php` might do include:
        *   Fetching and processing course-related information (e.g., course images, custom fields associated with a course, course contacts).
        *   Implementing logic for displaying courses in carousels or custom blocks.
        *   Providing helper methods for renderers or Mustache templates to access specific course data in a structured way.
    *   These classes help keep the theme's `lib.php` and renderer classes cleaner by offloading specialized tasks.

### 3. Styling (SCSS in `scss/`)

Moove uses SCSS (Sassy CSS), a powerful CSS preprocessor, to manage its styles in a modular and maintainable way.

*   **Variables (`scss/moove/_variables.scss`)**:
    *   **Importance**: This file is crucial for centralizing key design tokens. It typically defines:
        *   **Color Definitions**: Variables for primary, secondary, accent, text, background colors, etc. (e.g., `$primary-color`, `$text-color`).
        *   **Font Stacks**: Variables for preferred font families (e.g., `$font-family-sans-serif`).
        *   **Spacing and Sizing**: Variables for margins, paddings, border-radii, etc.
    *   Using variables ensures consistency across the theme and makes global style changes (like rebranding) much easier, as modifications only need to be made in one place. Many of these variables are dynamically set by PHP based on theme settings.

*   **Component-based SCSS (e.g., `_general.scss`, `_navbar.scss`, `_course.scss`, `_login.scss`)**:
    *   **Organization**: Moove's SCSS is typically organized into partial files (prefixed with an underscore, e.g., `_header.scss`, `_footer.scss`, `_course.scss`). Each file focuses on styling a specific component or section of the theme.
    *   `scss/moove/_general.scss` would contain base styles, typography, and general layout rules.
    *   Other files like `_navbar.scss` would style the navigation bar, `_course.scss` would style course listings and related elements.
    *   This modular approach makes the codebase easier to navigate, maintain, and debug. A main SCSS file (often `moodle.scss` or `styles.scss` in the `scss` root) imports these partials.

*   **Compiled CSS**:
    *   The SCSS code written by theme developers is not directly understood by browsers.
    *   Moodle (using its SCSS compilation capabilities, often triggered by functions in `lib.php`) compiles all the SCSS files (partials and main files) into a single or a few standard CSS files.
    *   The primary compiled CSS file for Moove is typically located at a path like `style/moodle.css` or similar within the theme directory, which is then loaded by the browser.

### 4. Templating (Mustache in `templates/`)

Moove utilizes Mustache templates for generating the HTML structure of its pages and components, promoting a separation of logic (PHP) and presentation (HTML).

*   **Page Structure (e.g., `templates/frontpage.mustache`)**:
    *   **Layout Definition**: Templates like `frontpage.mustache` define the overall HTML layout for specific page types. For the front page, this would include placeholders for the header, footer, slider, marketing spots, course sections, and other elements configured via theme settings.
    *   They use Mustache tags (e.g., `{{> theme_moove/shared/header }}`) to include reusable partial templates for common sections.

*   **Component Templates (e.g., `templates/core/user_menu.mustache`, `templates/components/course_card.mustache`)**:
    *   **UI Snippets**: Smaller, reusable UI components are defined in their own Mustache files. For instance, `templates/core/user_menu.mustache` would define the structure of the user dropdown menu in the header.
    *   A template for a course card might define how an individual course is displayed in a list or carousel.
    *   These component templates are then included in larger page templates or rendered directly by PHP renderers.

*   **Data Rendering**:
    *   **Dynamic Content**: Mustache templates are "logic-less," meaning they don't contain complex programming logic. Instead, they are populated with data provided by PHP.
    *   **Renderers as Data Source**: PHP renderers (discussed in section 2) prepare the data (often as objects or arrays) that the Mustache templates need. This data is then passed to the template engine.
    *   **Mustache Tags**: The templates use Mustache tags (e.g., `{{ sitename }}`, `{{#courses}} {{coursename}} {{/courses}}`) as placeholders. The template engine replaces these tags with the actual data provided by the renderer, generating the final HTML sent to the browser.

### 5. JavaScript Interactivity (`amd/src/` and embedded JS)

JavaScript is employed to enhance user experience with client-side interactivity, and Moove leverages Moodle's recommended practices for including it.

*   **AMD Modules (e.g., `amd/src/accessibilitybar.js`, `amd/src/frontpage.js`)**:
    *   **Structured JavaScript**: Moodle uses Asynchronous Module Definition (AMD) for organizing JavaScript into reusable modules. Moove follows this pattern by placing its custom JavaScript files in the `amd/src/` directory.
    *   **Functionality**: These modules can provide various functionalities, such as:
        *   `accessibilitybar.js`: Managing an accessibility toolbar (e.g., font size adjustments, contrast changes).
        *   `frontpage.js`: Initializing carousels/sliders on the front page, handling interactions with dynamic frontpage elements.
        *   Other modules could handle AJAX requests, form validation, or custom UI interactions.
    *   **Benefits**: AMD helps in managing dependencies between JavaScript files and improves performance by loading scripts asynchronously when they are needed.

*   **Embedded JS in Templates (`{{#js}}...{{/js}}`)**:
    *   **Initialization/Simple Interactions**: Moodle's Mustache templating system allows for embedding JavaScript directly within templates using the `{{#js}} ... {{/js}}` helper.
    *   **Use Cases**: This is often used for:
        *   **Initializing AMD Modules**: Passing data from PHP/templates to an AMD module or calling its initialization function. For example, the carousel in `frontpage.mustache` might be initialized this way, passing configuration options.
        *   **Simple Interactions**: For very small, self-contained scripts that are specific to a particular template and don't warrant a separate AMD module.
        *   Example from `frontpage.mustache`:
            ```mustache
            {{#js}}
            require(['theme_moove/frontpage'], function(FP) {
                FP.init();
            });
            {{/js}}
            ```
            This snippet calls the `init` function of the `theme_moove/frontpage` AMD module.

By combining these approaches, Moove provides a rich, customizable, and interactive user experience within the Moodle LMS.
