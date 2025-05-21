# WordPress Plugin Exercise

**Contributors:** IlyaT21
**Tags:** example, demo, custom post type, shortcode, REST API, AJAX, settings page
**Requires at least:** 5.0
**Tested up to:** 6.8
**License:** MIT
**License URI:** [https://opensource.org/licenses/MIT](https://opensource.org/licenses/MIT)

## Description

This plugin is a playground for testing and demonstrating a variety of WordPress features in a single package. It includes:

* **Plugin Declaration & Lifecycle Hooks**

  * Proper plugin header in `index.php`
  * Activation / deactivation hooks
  * Clean-up on uninstall via `uninstall.php`

* **Custom Post Type**

  * Registers a “Test Item” post type (in `includes/class-test-item-cpt.php`)
  * Adds custom metabox fields for extra data

* **Admin Settings Page**

  * Adds a submenu under **Settings → Test Plugin**
  * Uses the Settings API to store and retrieve plugin options

* **Shortcode**

  * Provides `[test_plugin_demo]` shortcode to display dynamic content on any page or post

* **REST API Endpoint**

  * Registers a custom endpoint under `/wp-json/test-plugin/v1/data`
  * Returns JSON data based on plugin settings or custom post type entries

* **AJAX & JavaScript**

  * Enqueues `js/script.js` on the front end and admin as needed
  * Demonstrates both front-end and admin AJAX handlers for dynamic interactions

* **Uninstall Cleanup**

  * Completely removes all custom options and database entries when deleted

## Installation

1. Upload the `wordPress-plugin-exercise` folder to your `/wp-content/plugins/` directory.
2. Activate the plugin through the “Plugins” screen in WordPress.
3. Configure plugin options via **Settings → Test Plugin**.

## Changelog

### 1.0.0

* Initial release with demo of CPT, shortcode, REST API, AJAX, and settings page.

## License

This plugin is released under the [MIT license](LICENSE).
