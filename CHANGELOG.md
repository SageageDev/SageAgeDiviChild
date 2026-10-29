# Changelog

All notable changes to the SageAge Divi child theme are documented in this file.

## [2.1.0] - 2026-10-29

### Added
- Divi 5 glue from the Blakehurst build, prefixed `sage-`, scripts in `js/` and stylesheets in `css/`, versioned by file mtime:
  - Fixed Theme Builder header: page content starts below it, it sits below the admin bar when logged in, and the opened mobile menu scrolls (`css/sage-header.css`, `js/sage-header-offset.js`). Applies only when a header section is set to Position: Fixed.
  - Mobile menu hamburger is keyboard-operable and reports `aria-expanded` (`js/sage-mobile-menu.js`).
  - Accordions start closed and the open item can be closed again (`css/sage-accordion.css`, `js/sage-accordion.js`, `render_block` filter).
  - Group Carousel images set to Open in Lightbox page through the whole carousel in the lightbox (`js/sage-carousel-lightbox.js`).
  - Team grid bio pop-up for a section with CSS class `sage-team` (`css/sage-team.css`, `js/sage-team.js`); colours are `--sage-team-*` variables.
  - Single posts: drops an imported post's first image when it repeats the featured image.
  - Events (The Events Calendar): sections with class `sage-has-events` / `sage-no-events` show only when there are / aren't upcoming events.
  - `[sage_category_dropdown all_url="/news/"]` shortcode for category archive templates.
  - Visual Builder fix for the Page Link / Post Link picker showing an empty selection for older pages (Divi 5.18 bug).

### Changed
- Moved `userway.js` to `js/userway.js`.

## [2.0.2] - 2026-08-11

### Added
- Added theme thumbnail (`screenshot.jpg`) for the WordPress theme selector.

### Changed
- Removed `custom-shortcodes.php` and its conditional `include()` in `functions.php`. Shortcode handling has moved to the "Community Content Shortcodes" plugin — configure site-specific values under Settings > Community Shortcodes.

## [2.0.1] - 2026-08-11

### Fixed
- `functions.php`: removed a broken standalone `__construct()` function that meant the "grant `unfiltered_html` to administrators" filter was never actually registered; replaced with a direct `add_filter()` call.
- `functions.php`: fixed a style handle mismatch (`dashicon` vs. `dashicons`) that prevented dashicons from being correctly removed for non-admin users on the front end.
- `login-editor.php`: fixed an undefined-variable bug (`$bg_url` instead of `$bg_size`) that silently disabled the login page's "Background Size" customizer option.
- `login-editor.php`: removed a dead `#loginform` border rule that referenced a `$form_border` variable with no corresponding customizer setting.

### Changed
- Removed the `header.php` template override, which was a near-verbatim copy of Divi's own header.php and a risk to Divi 5 compatibility. Its one real customization (the `no-referrer-when-downgrade` meta tag) now runs via a `wp_head` hook in `functions.php`, so the theme inherits Divi's header as-is regardless of version.
- `custom-shortcodes.php` was found empty; added a commented-out example shortcode as a starting template.

## [2.0.0] - Prior release

- Baseline version prior to this review.
