## Moove Theme: Technologies and Architecture

This document outlines the main technologies used in the Moove theme and its overall architecture.

### 1. Main Technologies Used

*   **PHP:** PHP is the core server-side language used in Moodle and the Moove theme. Its roles include:
    *   **Server-Side Logic:** Handling requests, processing data, and managing user interactions.
    *   **Theme Functions:** Defining theme-specific functionalities, such as modifying Moodle's default behavior, adding new settings, and preprocessing data for templates. This is evident in files like `lib.php`.
    *   **Renderers:** PHP classes (e.g., `core_renderer.php` in `classes/output/`) are used to generate HTML output. Moove overrides some of Moodle's core renderers to customize the look and feel.

*   **SCSS (Sassy CSS):** SCSS is a CSS preprocessor that extends the capabilities of standard CSS. In Moove, it's used for:
    *   **Styling:** Defining the visual appearance of the theme. Files like `scss/moove/_variables.scss` and `scss/moove/_general.scss` showcase its use.
    *   **Variables:** Using variables (e.g., for colors, fonts, spacing) to maintain consistency and facilitate easy customization. `_variables.scss` is a key file for this.
    *   **Compilation:** SCSS code is compiled into standard CSS that browsers can understand. The theme includes mechanisms (often in `lib.php`) to handle this compilation, potentially dynamically based on theme settings.

*   **Mustache Templates:** Mustache is a logic-less templating language. In Moove, it's used for:
    *   **HTML Structure:** Defining the HTML markup for various components and pages. Examples include `templates/frontpage.mustache` and `templates/core/user_menu.mustache`.
    *   **Data Rendering:** These templates are populated with data provided by PHP (often via renderers) to generate the final HTML. They keep the presentation logic separate from the business logic.

*   **JavaScript (AMD modules):** JavaScript is used for client-side interactivity.
    *   **Client-Side Interactivity:** Enhancing user experience with dynamic content updates, animations, and interactive elements without requiring page reloads.
    *   **Moodle's AMD System:** Moodle uses Asynchronous Module Definition (AMD) for organizing JavaScript code into reusable modules. This helps in managing dependencies and improving performance by loading scripts asynchronously. While not explicitly detailed in the provided file list, it's a standard Moodle practice and Moove would leverage it for any custom JavaScript.

### 2. Overall Architecture

*   **Parent Theme (Boost):** Moove inherits from Moodle's core "Boost" theme. This means:
    *   It leverages the foundational structure, styles, and functionalities provided by Boost.
    *   Moove can selectively override or extend Boost's features, reducing the need to build everything from scratch.
    *   Updates to the Boost theme in Moodle core can potentially influence Moove, requiring compatibility checks and adjustments.

*   **Renderer Overrides:** Moove customizes Moodle's HTML output by overriding core renderer methods.
    *   Moodle uses a system of renderers (PHP classes) to generate HTML for different parts of the UI.
    *   By creating its own renderer classes (e.g., `theme_moove_core_renderer` extending `core_renderer`) or by overriding specific methods within these classes, Moove can alter the HTML structure and add its own custom elements or classes. This is a powerful way to achieve a unique look and feel. The file `classes/output/core_renderer.php` is an example of this.

*   **Settings System:** The theme's customization options are managed through Moodle's settings system.
    *   `settings.php`: This file defines the settings that administrators can configure for the Moove theme (e.g., colors, logos, layout options).
    *   These settings are stored in the database and can be accessed by PHP functions (often in `lib.php`) and renderers to modify the theme's behavior and appearance dynamically. For instance, a setting might control which SCSS variables are used.

*   **SCSS Compilation Process:** The theme dynamically compiles SCSS into CSS, often influenced by its settings.
    *   Functions in `lib.php` (e.g., `theme_moove_get_main_scss_content`, `theme_moove_get_extra_scss`) are responsible for gathering SCSS code, including variables defined by theme settings.
    *   This allows administrators to customize aspects like the primary color or font choices through the UI, and these choices are then injected into the SCSS before it's compiled into the final CSS delivered to the browser. This makes the theme highly adaptable without requiring direct code modification for common visual changes.
    *   The `config.php` file usually specifies the theme's name, version, and dependencies, including the parent theme (Boost).
    *   Utility classes like `classes/util/course.php` might provide helper functions for theme-specific logic related to courses or other Moodle entities, further tailoring the user experience.
