# Changelog

All notable changes to the SageAge Divi child theme are documented in this file.

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
