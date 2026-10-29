<?php

function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'dashicons', '/wp-includes/css/dashicons.min.css?ver=6.1.1');
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

// No-referrer-when-downgrade meta tag (previously required a full header.php override)
add_action( 'wp_head', function() {
	echo '<meta name="referrer" content="no-referrer-when-downgrade" />' . "\n";
}, 1 );

// Custom Shortcodes now handled by the "Community Content Shortcodes" plugin.
// Fill in this site's values under Settings > Community Shortcodes.

add_action( 'init', function() { delete_option( 'schema-ActionScheduler_StoreSchema' ); } );

add_filter( 'the_title', 'do_shortcode' );
add_filter( 'single_post_title', 'do_shortcode' );

include('login-editor.php');

function year_shortcode() {
  $year = date('Y');
  return $year;
}
add_shortcode('year', 'year_shortcode');


function dpdfg_after_read_more() {
    $event_start = get_post_meta( get_the_ID(), '_event_start', true );
	$start_date = date('F j, Y', strtotime($event_start));
	$start_time = date('g:i a', strtotime($event_start));
    return '<div class="dpdfg-eventdate">'.$start_date.'</div><div class="dpdfg-eventtime">'.$start_time.'</div>';
}

add_filter('dpdfg_after_read_more', 'dpdfg_after_read_more');

/**Userway*/
add_action('wp_enqueue_scripts', 'qg_enqueue');
function qg_enqueue() {
	wp_enqueue_script( 'userway-js', get_stylesheet_directory_uri() . '/js/userway.js', array( 'jquery' ),'',true );

}

function mytheme_enqueue_style() {
wp_enqueue_style('custom_font', '//use.typekit.net/lsl7bwp.css', array(), null, 'all');
}
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_style' );

/*grant unfiltered to all admin accounts*/
add_filter( 'map_meta_cap', 'add_unfiltered_html_capability_to_role', 1, 3 );
function add_unfiltered_html_capability_to_role( $caps, $cap, $user_id ) {
	if ( 'unfiltered_html' === $cap && user_can( $user_id, 'administrator' ) ) {
		$caps = array( 'unfiltered_html' );
	}
	return $caps;
}

add_filter( 'gform_confirmation_anchor', '__return_true' );

// remove dashicons in frontend to non-admin 
function wpdocs_dequeue_dashicon() {
  if (current_user_can( 'update_core' )) {
      return;
  }
  wp_deregister_style('dashicons');
}
add_action( 'wp_enqueue_scripts', 'wpdocs_dequeue_dashicon' );


//admin styles
function custom_adminstyle() {
  echo '<style>
  .widefat tfoot tr td, .widefat tfoot tr th, .widefat thead tr td, .widefat thead tr th {width:40px!important;}
  .widefat tfoot tr td, .widefat tfoot tr th, .widefat thead tr td, .widefat thead tr th#title{width:60px!important}
  .widefat thead td.check-column{width:15px!important}
  </style>';
}
add_action('admin_head', 'custom_adminstyle');

//Disable ajax on Gravity Forms site wide
add_filter( 'gform_disable_ajax_save', 'disable_ajax_save_all_forms' );

function disable_ajax_save_all_forms( $disabled ) {
    return true;
}

//remove indication on GF
 add_filter( 'gform_required_legend' , '__return_empty_string' );

 //disable all comments
function disable_comments_status() {
return false;
}
add_filter('comments_open', 'disable_comments_status', 20, 2);

function disable_comments_admin_menu() {
remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'disable_comments_admin_menu');

function disable_comments_admin_bar() {
remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 50);
}
add_action('admin_bar_menu', 'disable_comments_admin_bar', 999);

function disable_comments_dashboard_widgets() {
remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'disable_comments_dashboard_widgets');

function acf_field_shortcode($atts) {
    $atts = shortcode_atts(array(
        'field' => '',
        'post_id' => false,
    ), $atts, 'acf');

    if (function_exists('get_field')) {
        $field_value = get_field($atts['field'], $atts['post_id']);

        // If the field is an array (like a select, relationship, or taxonomy field)
        if (is_array($field_value)) {
            $output = [];

            foreach ($field_value as $item) {
                if (is_numeric($item)) {
                    // Check if it's a post ID
                    $post = get_post($item);
                    if ($post) {
                        $output[] = get_the_title($post->ID);
                        continue;
                    }

                    // Check if it's a taxonomy term ID
                    $term = get_term($item);
                    if (!is_wp_error($term) && !empty($term)) {
                        $output[] = $term->name;
                        continue;
                    }
                }

                // If it's not a number, just add the value
                $output[] = $item;
            }

            return esc_html(implode(', ', $output));
        }

        // If it's a single post ID, get the post title
        if (is_numeric($field_value)) {
            $post = get_post($field_value);
            if ($post) {
                return esc_html(get_the_title($post->ID));
            }

            $term = get_term($field_value);
            if (!is_wp_error($term) && !empty($term)) {
                return esc_html($term->name);
            }
        }

        return esc_html($field_value);
    }

    return '';
}
add_shortcode('acf', 'acf_field_shortcode');


/* ------------------------------------------------------------------------------------------------------------------
   Divi 5 glue. Each feature is a script in js/ and/or a stylesheet in css/, prefixed sage-.
   Versioned by file mtime, not the theme Version: WP Engine caches assets for a year, so edits must change the URL.
   ------------------------------------------------------------------------------------------------------------------ */

function sage_enqueue_asset( $handle, $deps = array() ) {
	foreach ( array( 'js' => 'js', 'css' => 'css' ) as $dir => $ext ) {
		$file = "/$dir/$handle.$ext";
		if ( ! file_exists( get_stylesheet_directory() . $file ) ) {
			continue;
		}
		$url = get_stylesheet_directory_uri() . $file;
		$ver = filemtime( get_stylesheet_directory() . $file );
		'js' === $ext ? wp_enqueue_script( $handle, $url, $deps, $ver, true ) : wp_enqueue_style( $handle, $url, array(), $ver );
	}
}

add_action( 'wp_enqueue_scripts', 'sage_enqueue_divi5_glue' );
function sage_enqueue_divi5_glue() {
	// Fixed Theme Builder header: page content starts below it, the admin bar no longer covers it, and the
	// opened mobile menu scrolls. The CSS only applies when a header section is set to Position: Fixed.
	sage_enqueue_asset( 'sage-header' );
	sage_enqueue_asset( 'sage-header-offset' );
	// Mobile menu: keyboard-operable hamburger with aria-expanded.
	sage_enqueue_asset( 'sage-mobile-menu' );
	// Accordions: the open item can be closed again (see the render_block filter below).
	sage_enqueue_asset( 'sage-accordion' );
	// Group Carousels with images set to Open in Lightbox: the lightbox pages through the carousel's images.
	sage_enqueue_asset( 'sage-carousel-lightbox', array( 'jquery' ) );
	// Team grid (section with CSS class sage-team): click a team member to read the bio in a pop-up.
	sage_enqueue_asset( 'sage-team' );
}

// Accordions (FAQs): all items start closed. Divi 5 always renders the first item open and has no setting for it.
add_filter( 'render_block_divi/accordion-item', 'sage_accordion_start_closed' );
function sage_accordion_start_closed( $html ) {
	if ( ! empty( $_GET['et_fb'] ) ) {
		return $html;
	}
	return preg_replace( '/\bet_pb_toggle_open\b/', 'et_pb_toggle_close', $html, 1 );
}

// Single posts (Theme Builder template with the featured image in the hero): drop the featured image when an
// imported post repeats it as the first thing in its content.
add_filter( 'the_content', 'sage_remove_repeated_featured_image', 5 );
function sage_remove_repeated_featured_image( $content ) {
	// Theme Builder renders the content outside the main loop, so check the viewed post rather than in_the_loop().
	$post_id = get_queried_object_id();
	if ( ! is_singular( 'post' ) || get_the_ID() !== $post_id || ! has_post_thumbnail( $post_id ) ) {
		return $content;
	}
	$thumb_id = get_post_thumbnail_id( $post_id );
	$file     = preg_replace( '/(-\d+x\d+)?(-scaled)?\.[a-z]+$/i', '', basename( wp_get_attachment_url( $thumb_id ) ) );
	return preg_replace_callback(
		'/^\s*(?:<p>\s*)?(?:<a[^>]*>\s*)?<img[^>]*>(?:\s*<\/a>)?(?:\s*<\/p>)?/i',
		function ( $m ) use ( $thumb_id, $file ) {
			$is_featured = false !== strpos( $m[0], 'wp-image-' . $thumb_id . '"' ) || false !== strpos( $m[0], 'wp-image-' . $thumb_id . ' ' ) || false !== strpos( $m[0], '/' . $file . '.' ) || false !== strpos( $m[0], '/' . $file . '-' );
			return $is_featured ? '' : $m[0];
		},
		$content,
		1
	);
}

// Events pages (The Events Calendar): sections with CSS class sage-has-events show only while there are upcoming
// events, and sections with sage-no-events only when there are none. Both stay editable in the Visual Builder.
add_filter( 'render_block_divi/section', 'sage_events_sections', 10, 2 );
function sage_events_sections( $html, $block ) {
	$class = $block['attrs']['module']['advanced']['htmlAttributes']['desktop']['value']['class'] ?? '';
	if ( ! preg_match( '/\bsage-(has|no)-events\b/', $class, $m ) || ! function_exists( 'tribe_get_events' ) || ! empty( $_GET['et_fb'] ) ) {
		return $html;
	}
	static $has_events = null;
	if ( null === $has_events ) {
		$has_events = (bool) tribe_get_events( array( 'posts_per_page' => 1, 'ends_after' => 'now', 'fields' => 'ids' ) );
	}
	return ( 'has' === $m[1] ) === $has_events ? $html : '';
}

// Category dropdown for a Theme Builder "Category Archives" template: [sage_category_dropdown all_url="/news/"]
// Picking a category goes to its archive; "All Categories" goes to all_url, defaulting to the current page.
// On pages, use Divi's own Post Filter module with the Loop instead (AJAX).
add_shortcode( 'sage_category_dropdown', 'sage_category_dropdown' );
function sage_category_dropdown( $atts ) {
	$atts    = shortcode_atts( array( 'all_url' => '' ), $atts );
	$all_url = $atts['all_url'] ? home_url( $atts['all_url'] ) : ( is_singular() ? get_permalink() : home_url( '/' ) );
	$current = is_category() ? get_queried_object_id() : 0;
	$options = '<option value="' . esc_url( $all_url ) . '">All Categories</option>';
	foreach ( get_categories( array( 'exclude' => array( get_option( 'default_category' ) ) ) ) as $cat ) {
		$options .= sprintf( '<option value="%s"%s>%s</option>', esc_url( get_category_link( $cat ) ), selected( $current, $cat->term_id, false ), esc_html( $cat->name ) );
	}
	return '<div class="sage-cat-filter"><label class="screen-reader-text" for="sage-cat-filter">Filter by category</label>'
		. '<select id="sage-cat-filter" onchange="if(this.value)location.href=this.value">' . $options . '</select></div>';
}

// Visual Builder: the Page Link / Post Link dynamic content picker loads only the 25 newest items, so a button
// linked to an older page shows an empty selection. Return every item for that first unsearched request instead.
// Divi 5.18 bug; remove once Divi loads the saved item itself.
add_action( 'rest_api_init', 'sage_vb_post_link_picker_fix' );
function sage_vb_post_link_picker_fix() {
	foreach ( get_post_types( array( 'show_in_rest' => true ) ) as $post_type ) {
		add_filter( "rest_{$post_type}_query", function ( $args, $request ) {
			if ( 'id,title,slug' === $request['_fields'] && empty( $request['search'] ) && empty( $request['slug'] )
				&& 1 === (int) $request['page'] && current_user_can( 'edit_posts' ) ) {
				$args['posts_per_page'] = 500;
			}
			return $args;
		}, 10, 2 );
	}
}
