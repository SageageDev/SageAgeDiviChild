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

// Only load Custom Shortcodes on true front-end (not admin, not Divi builder)
if ( !is_admin() && !wp_doing_ajax() && !isset($_GET['et_fb']) && !isset($_GET['et_bfb']) ) {
    include('custom-shortcodes.php');
}

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
	wp_enqueue_script( 'userway-js', get_stylesheet_directory_uri() . '/userway.js', array( 'jquery' ),'',true );

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