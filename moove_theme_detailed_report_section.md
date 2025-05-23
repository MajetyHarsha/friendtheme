## Moove Theme: Technical Deep Dive

This section provides a detailed analysis of the Moove theme's internal architecture, covering its configuration management, PHP class structure, styling mechanisms, templating system, and JavaScript interactivity, based on an examination of its core files.

### 1. Configuration Management

The Moove theme's configuration is primarily managed through `config.php`, `settings.php`, and SCSS-related functions within `lib.php`.

*   **`config.php`**: This file is the entry point for Moodle to understand the theme's basic properties and capabilities.
    *   **Basic Theme Definition**: It declares the theme's machine-readable name (e.g., `theme_moove`), which is essential for Moodle's theme management system.
    *   **Layouts**: It defines the page layouts the theme supports, such as `frontpage`, `standard`, `login`, `maintenance`, etc. These layouts correspond to different page structures used throughout a Moodle site.
    *   **Parent Theme**: A crucial declaration in `config.php` is the parent theme, typically `['boost']`. This signifies that Moove inherits from Moodle's core "Boost" theme, allowing it to utilize and extend Boost's features and styles.
    *   **SCSS Callback**: It specifies the PHP function that Moodle will call to get the theme's SCSS content for compilation. This is usually `theme_moove_get_main_scss_content` (defined in `lib.php`), enabling dynamic styling based on settings.
    *   **Icon System**: `config.php` may also declare support for Moodle's core icon system or a theme-specific one, ensuring consistent icon usage.

*   **`settings.php`**: This file defines the comprehensive set of customization options available to administrators via the Moodle UI (Appearance > Moove > Settings). These settings allow for significant personalization without direct code modification.
    *   **Examples of Settings**:
        *   **Branding**: Uploading a custom logo (`logo`), compact logo (`logocompact`), and favicon (`favicon`).
        *   **Colors**: Defining key brand colors like `brandcolor`, `brandsuccess`, `brandinfo`, `brandwarning`, `branddanger`.
        *   **Fonts**: Selecting font families for main content and headings (e.g., `fontname`, `fontnameheadings`).
        *   **Frontpage Elements**:
            *   **Slider**: Settings for enabling (`slideronoff`) and configuring up to five slides (`sliderimage1`, `sliderheading1`, `slidercontent1`, `sliderbuttontext1`, `sliderbuttonurl1`, etc.).
            *   **Marketing Boxes**: Enabling (`displaymarketingbox`) and configuring content for multiple marketing spots (`marketingheading1`, `marketingcontent1`, `marketingbuttontext1`, `marketingbuttonurl1`, etc.).
            *   **FAQs**: Enabling (`displayfaq`) and defining questions and answers for a FAQ section (`faqquestion1`, `faqanswer1`, etc.).
        *   **Footer Links**: Configuring custom links and sections in the site footer (`footercontent`, `privacylink`, social media links).
    *   **Storage and Access**: These settings are stored in Moodle's database. Within the theme's PHP code (especially in `lib.php` and renderers), they are accessed using the global `$theme` object, for instance, `$theme->settings->brandcolor` or `get_config('theme_moove', 'logo')`.

*   **`lib.php` (SCSS related functions)**: This file contains the logic for dynamically generating the SCSS code that styles the theme. This is a powerful feature allowing UI settings to directly influence the theme's appearance.
    *   **`theme_moove_get_main_scss_content($theme)`**: This is the primary function (often registered in `config.php`) that Moodle calls to get the complete SCSS for the theme. It acts as an aggregator.
    *   **`theme_moove_get_pre_scss($theme, $variables_scss)`**: This function is crucial for injecting SCSS variables based on theme settings. It typically:
        *   Retrieves settings like `brandcolor`, font choices, etc., using `$theme->settings`.
        *   Generates SCSS variable declarations (e.g., `$primary: {{brandcolor}};`, `$font-family-sans-serif: "{{fontname}}", sans-serif;`).
        *   It also incorporates predefined color presets if selected in the theme settings. The `variables_scss` parameter usually contains the content of `scss/moove/_variables.scss` which it prepends with these dynamic variable definitions.
    *   **`theme_moove_get_extra_scss($theme)`**: This function appends additional SCSS to the main SCSS content. It's used for:
        *   Including SCSS from a custom SCSS input field in the theme settings (`customscss`).
        *   Adding styles for specific Moodle blocks or plugins that Moove explicitly supports.
        *   Potentially loading different SCSS files based on other theme settings (e.g., a "dark mode" stylesheet).
    *   **Collaboration**: These functions work in concert: `theme_moove_get_main_scss_content` calls `theme_moove_get_pre_scss` to get the dynamically generated variables and base SCSS, then appends the content of other structural SCSS files (like `_general.scss`, `_navbar.scss`, etc.), and finally appends any "extra" SCSS from `theme_moove_get_extra_scss`. This combined string is then returned to Moodle for compilation into CSS.

### 2. PHP Classes (`classes/` directory)

The `classes/` directory contains PHP classes that override or extend Moodle's core functionalities, particularly for HTML generation and theme-specific logic.

*   **Renderers (e.g., `classes/output/core_renderer.php` - `theme_moove_core_renderer`)**: Moove extensively uses custom renderers to modify the HTML output of Moodle. `theme_moove_core_renderer` extends Moodle's `core_renderer`.
    *   **`standard_head_html()`**:
        *   **Customization**: Adds Google Analytics code if `gaid` setting is present. It also includes custom meta tags, font preloading (if specified in settings), and potentially custom JavaScript for the `<head>` section.
    *   **`body_attributes()`**:
        *   **Customization**: Adds custom CSS classes to the `<body>` tag based on theme settings. This is used for features like the accessibility "dyslexia-friendly font" (`bodycss`) or different layout styles (e.g., boxed vs. full-width).
    *   **`render_login(login_info $output)`**:
        *   **Customization**: Overrides the standard Moodle login page rendering to implement Moove's custom login page design. This includes potentially showing a custom background image (`loginbackgroundimage`), the theme logo, and a specific layout for the login form and any associated messages.
    *   **`favicon()`**:
        *   **Customization**: Ensures that the favicon specified in the theme settings (`$theme->settings->favicon`) is used, or defaults to a theme-provided one if not set.
    *   **`navbar()` (and related methods like `render_navbar_output`, `render_custom_menu_item`)**:
        *   **Customization**: Significantly alters the main navigation bar. This can include integrating the theme's compact logo (`logocompact`), changing the structure of menu items, adding custom menu items, and styling the navbar according to Moove's design. It also manages the display of the language menu and user menu.

*   **Utility Classes (e.g., `classes/util/course.php` - `theme_moove_util_course`)**: These classes provide helper methods to fetch and process Moodle data for use in templates and renderers, encapsulating theme-specific logic.
    *   **`get_summary_image($course)`**:
        *   **Functionality**: This method likely attempts to find a suitable image for a course summary. It might look for course overview files, or a default image if none is found. This supports features like displaying course images in course listings or carousels on the front page.
    *   **`get_course_contacts($courseid)`**:
        *   **Functionality**: Retrieves users who are designated as "contacts" for a specific course (e.g., teachers or specific roles). This data can be used in course pages or course information blocks to display contact information.
    *   **`get_category($categoryid)`**:
        *   **Functionality**: Fetches information about a course category, such as its name and description. This can be used to display category information on category pages or in breadcrumbs.
    *   These methods simplify the logic in renderers and templates by providing pre-processed or specifically fetched data, supporting features like custom course listings, enhanced user profiles with course roles, or enriched category displays.

### 3. Styling (SCSS in `scss/`)

Moove uses SCSS (Sassy CSS) for a modular, maintainable, and dynamic styling approach. The main SCSS files are typically located in `scss/moove/`.

*   **Variables (`scss/moove/_variables.scss`)**:
    *   **Central Role**: This file is fundamental for theme consistency. It defines a wide range of SCSS variables for colors, typography, spacing, breakpoints, etc.
    *   **Usage**: These variables (e.g., `$primary`, `$body-bg`, `$font-family-base`, `$grid-breakpoints`) are used extensively throughout all other `_*.scss` partial files.
    *   **Dynamic Derivation**: Crucially, many variables in `_variables.scss` are either directly set or have their default values overridden by the SCSS generated in `lib.php` based on theme settings. For example, `$primary: {{brandcolor}};` (where `{{brandcolor}}` is replaced by the PHP function with the setting value) ensures that the admin's chosen brand color is used as the primary color throughout the theme.

*   **Component-based SCSS (e.g., `_general.scss`, `_navbar.scss`, `_course.scss`, `_frontpage.scss`)**:
    *   **Modular Approach**: Moove organizes its styles into numerous partial SCSS files (prefixed with `_`), each dedicated to a specific UI component, page layout, or section of the theme.
        *   `_general.scss`: Contains base HTML element styling, typography, layout helpers, and other global styles.
        *   `_navbar.scss`: Styles for the main navigation bar.
        *   `_course.scss`: Styles related to course listings, course pages, and course content.
        *   `_frontpage.scss`: Specific styles for the elements appearing on the Moove front page (slider, marketing boxes, etc.).
        *   Other files would target login (`_login.scss`), footers (`_footer.scss`), specific Moodle activities, etc.
    *   **Import Mechanism**: These partial files are typically imported into a main SCSS file (e.g., `default.scss` or `moodle.scss` within the `scss/` directory, or their contents are directly included by the PHP functions in `lib.php` when assembling the final SCSS string). This creates a single SCSS stream for compilation.

*   **Compiled CSS**:
    *   **Final Output**: The entire SCSS codebase (dynamically generated variables + static partials) is compiled by Moodle into standard CSS files.
    *   **Location**: The primary compiled CSS file is usually found at a path like `[theme_directory]/style/moodle.css` (or a similar name). This is the actual stylesheet loaded by the browser to render the theme's appearance.

### 4. Templating (Mustache in `templates/`)

Moove employs Mustache templates for rendering HTML, ensuring a separation between PHP logic and presentation.

*   **Page Structure (e.g., `templates/frontpage.mustache`)**:
    *   **Conditional Rendering**: This template heavily utilizes Mustache sections to conditionally render large parts of the front page based on theme settings and data passed from PHP.
        *   `{{#slidercount}} ... {{/slidercount}}`: Renders the slider section only if `slidercount` (derived from theme settings indicating enabled slides) is greater than zero. Inside this section, loops like `{{#slides}} ... {{/slides}}` would iterate over slide data.
        *   `{{#displaymarketingbox}} ... {{/displaymarketingbox}}`: Controls the visibility of the marketing boxes area based on the corresponding theme setting. Similar logic applies to `{{#displayfaq}}` for the FAQ section.
    *   **Includes**: It uses Mustache partials (`{{> theme_moove/shared/header }}`, `{{> theme_moove/frontpage/slider }}`) to include reusable HTML structures.

*   **Component Templates (e.g., `templates/core/user_menu.mustache`)**:
    *   **User Menu Structure**: This template defines the HTML for the user menu in the header.
    *   **Authenticated/Unauthenticated Users**: It uses sections like `{{#userloggedin}} ... {{/userloggedin}}` and `{{^userloggedin}} ... {{/userloggedin}}` to show different menu items for logged-in users (profile, grades, messages, logout) versus guests (login link).
    *   **Dropdown Structure**: It builds the dropdown menu structure using appropriate HTML and CSS classes that are styled by SCSS.
    *   **Accessibility Integration**: It includes elements related to accessibility, such as links or buttons that trigger accessibility settings. The code snippet `require(['theme_moove/accessibilitysettings'], function(AccessibilitySettings) { new AccessibilitySettings('[data-action="show-accessibility-settings"]') });` within a `{{#js}}` block in this template indicates that it initializes JavaScript for an accessibility settings modal.

*   **Data Rendering**:
    *   **Context Variables**: PHP renderers prepare data (context variables) and pass it to the Mustache engine. These variables are accessed in templates.
        *   `{{{variable_name}}}`: Used for outputting HTML content (unescaped). For example, `{{{ output.full_header }}}` in a layout template.
        *   `{{variable_name}}`: Used for outputting plain text (HTML special characters are escaped). For example, `{{ sitename }}`.
        *   `{{#section}} ... {{/section}}`: Used for conditional rendering if `section` is true or not empty, or for iterating over lists of objects. Inside the loop, `{{field_name}}` accesses properties of the current object. For instance, `{{#marketingboxes}} {{heading}} {{/marketingboxes}}`.
    *   **Data Flow**: Data flows from Moodle's core APIs -> theme utility classes (optional) -> theme renderers (which process and structure the data) -> Mustache templates (which display the data).

### 5. JavaScript Interactivity (`amd/src/` and embedded JS)

JavaScript enhances the user experience with client-side features, using both AMD modules and embedded scripts.

*   **AMD Modules (e.g., `amd/src/accessibilitybar.js`, `amd/src/accessibilitysettings.js`)**:
    *   **Role**: These modules provide complex client-side functionalities.
        *   `accessibilitybar.js`: Likely manages the functionality of a floating accessibility toolbar (e.g., font size adjustment, contrast changes).
        *   `accessibilitysettings.js` (referenced in `user_menu.mustache`): Handles the display and interaction logic for an accessibility settings modal, allowing users to customize their experience (e.g., font type, color schemes).
    *   **Build Process**: Moodle's build process (typically run via Grunt) compiles and minifies these source JavaScript files from `amd/src/` into optimized versions located in the `amd/build/` directory. These built files are what's actually loaded by the browser.

*   **Embedded JS in Templates (`{{#js}}...{{/js}}`)**:
    *   **Carousel Initialization**: In `templates/frontpage.mustache`, JavaScript for initializing the frontpage carousel is often embedded:
        ```mustache
        {{#js}}
        require(['jquery', 'theme_boost/bootstrap/carousel'], function($, carousel) {
            $('.carousel').carousel();
        });
        {{/js}}
        ```
        This ensures that the carousel is activated once its HTML structure is loaded.
    *   **Loading AMD Modules and Initializing Moodle Core JS**: Templates also use `{{#js}}` to load Moodle's core JavaScript modules or specific theme modules:
        ```mustache
        {{#js}}
        require(['theme_boost/loader', 'theme_boost/drawer'], function(Loader, Drawer) {
            Drawer.init(); // Example of initializing a Boost component
        });
        require(['theme_moove/accessibilitysettings'], function(AccessibilitySettings) {
            new AccessibilitySettings('[data-action="show-accessibility-settings"]');
        });
        {{/js}}
        ```
        This approach is common for integrating page-specific JavaScript behaviors or initializing components that are part of the Boost parent theme or Moove itself.

This detailed structure allows the Moove theme to be highly configurable, visually distinct, and interactive, providing a tailored user experience on top of the Moodle LMS.
