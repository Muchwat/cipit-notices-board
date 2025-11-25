=== CIPIT Custom Notices Board ===
Contributors: Kevin Muchwat
Tags: notices, announcements, shortcode, custom post type, grid, responsive
Requires at least: 5.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

CIPIT Custom Notices Board implements a robust system for managing and displaying structured, card-based notices using a Custom Post Type (CPT) and a shortcode. It's designed to seamlessly integrate with modern, CSS variable-driven themes.

== Description ==

The CIPIT Custom Notices Board plugin provides a structured and customizable solution for publishing announcements, vacancies, and general notices on your WordPress site.

Key Features:
* **Custom Post Type (CPT):** Creates a dedicated "CIPIT Notices Board" menu in the admin dashboard for easy content management.
* **Custom Meta Fields:** Includes fields for Icon Class (Font Awesome), a brief card Description, and Button Text, removing the need for external ACF/Meta Box plugins.
* **Custom Taxonomy:** Allows categorization of notices (e.g., 'Job Vacancy', 'Announcement') using "Notice Categories."
* **Shortcode Driven:** Display the notice cards anywhere using the `[cipit_notices]` shortcode.
* **Customizable Query:** Filter notices by category and limit the display count directly via shortcode attributes.
* **Modern, Responsive Design:** Uses a clean, grid-based card layout that is fully responsive, leveraging CSS variables for theme integration.

== Installation ==

1.  **Upload:** Upload the `cipit-notices` directory (containing `cipit-notices.php` and this `readme.txt`) to the `/wp-content/plugins/` directory.
2.  **Activate:** Activate the plugin through the 'Plugins' menu in WordPress.

== Usage ==

### 1. Adding Notices

1.  Navigate to the new **CIPIT Notices Board** menu in your WordPress admin.
2.  Go to **Notice Categories** and create your required categories (e.g., 'Course Open', 'Job Vacancy').
3.  Click **Add New** under the **CIPIT Notices Board** menu.
4.  Enter the **Title** (this will be the card heading).
5.  Fill out the **Notice Card Details** meta box with:
    * **Icon Class:** (e.g., `fas fa-graduation-cap`)
    * **Brief Card Description:** (The short summary for the card)
    * **Read More Button Text:** (e.g., "View Details")
6.  Assign a **Notice Category**.
7.  Publish the notice.

### 2. Displaying the Board (Shortcode)

Place the following shortcode on any Page, Post, or Widget area that supports shortcodes:

**Basic Display:**
[cipit_notices]

*(Displays the 3 latest notices with the title "Latest Notices.")*

**Advanced Display:**

That's a necessary component for a professional WordPress plugin! A readme.txt file uses a standardized format recognized by the WordPress.org plugin repository. Even if you aren't uploading it there, it's the standard place to put documentation, installation instructions, and basic information.

Here is the complete readme.txt file content for your CIPIT Custom Notices Board plugin (cipit-notices.php).

Plaintext

=== CIPIT Custom Notices Board ===
Contributors: Kevin Muchwat
Tags: notices, announcements, shortcode, custom post type, grid, responsive
Requires at least: 5.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

CIPIT Custom Notices Board implements a robust system for managing and displaying structured, card-based notices using a Custom Post Type (CPT) and a shortcode. It's designed to seamlessly integrate with modern, CSS variable-driven themes.

== Description ==

The CIPIT Custom Notices Board plugin provides a structured and customizable solution for publishing announcements, vacancies, and general notices on your WordPress site.

Key Features:
* **Custom Post Type (CPT):** Creates a dedicated "CIPIT Notices Board" menu in the admin dashboard for easy content management.
* **Custom Meta Fields:** Includes fields for Icon Class (Font Awesome), a brief card Description, and Button Text, removing the need for external ACF/Meta Box plugins.
* **Custom Taxonomy:** Allows categorization of notices (e.g., 'Job Vacancy', 'Announcement') using "Notice Categories."
* **Shortcode Driven:** Display the notice cards anywhere using the `[cipit_notices]` shortcode.
* **Customizable Query:** Filter notices by category and limit the display count directly via shortcode attributes.
* **Modern, Responsive Design:** Uses a clean, grid-based card layout that is fully responsive, leveraging CSS variables for theme integration.

== Installation ==

1.  **Upload:** Upload the `cipit-notices` directory (containing `cipit-notices.php` and this `readme.txt`) to the `/wp-content/plugins/` directory.
2.  **Activate:** Activate the plugin through the 'Plugins' menu in WordPress.

== Usage ==

### 1. Adding Notices

1.  Navigate to the new **CIPIT Notices Board** menu in your WordPress admin.
2.  Go to **Notice Categories** and create your required categories (e.g., 'Course Open', 'Job Vacancy').
3.  Click **Add New** under the **CIPIT Notices Board** menu.
4.  Enter the **Title** (this will be the card heading).
5.  Fill out the **Notice Card Details** meta box with:
    * **Icon Class:** (e.g., `fas fa-graduation-cap`)
    * **Brief Card Description:** (The short summary for the card)
    * **Read More Button Text:** (e.g., "View Details")
6.  Assign a **Notice Category**.
7.  Publish the notice.

### 2. Displaying the Board (Shortcode)

Place the following shortcode on any Page, Post, or Widget area that supports shortcodes:

**Basic Display:**
[cipit_notices]

*(Displays the 3 latest notices with the title "Latest Notices.")*

**Advanced Display:**
[cipit_notices count="5" title="Latest Job Vacancies" category="job-vacancy"]

*(Displays the 5 latest notices filtered by the category slug 'job-vacancy', with a custom title.)*

**Shortcode Attributes:**
* `count` (integer): The number of notices to display. Default: `3`.
* `title` (string): The title for the section (the `<h2>` tag). Default: `"Latest Notices"`.
* `category` (string): The slug of the Notice Category taxonomy to filter the results by. Default: `""` (all categories).

== Changelog ==

= 1.0.0 =
* Initial release.
* Added CPT ('notice') and Taxonomy ('notice_category').
* Implemented custom meta boxes for card data (Icon, Description, Button Text).
* Created `[cipit_notices]` shortcode with filtering and counting attributes.
* Injected required CSS for the card design and responsiveness.