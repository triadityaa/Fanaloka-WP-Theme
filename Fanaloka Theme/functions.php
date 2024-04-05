<?php
/**
 * Theme functions and definitions
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! isset( $content_width ) ) {
	$content_width = 800; // pixels
}

/*
 * Set up theme support
 */
if ( ! function_exists( 'hello_elementor_theme_setup' ) ) {
	function hello_elementor_theme_setup() {
		if ( apply_filters( 'hello_elementor_theme_load_textdomain', true ) ) {
			load_theme_textdomain( 'elementor-hello-theme', get_template_directory() . '/languages' );
		}

		if ( apply_filters( 'hello_elementor_theme_register_menus', true ) ) {
			register_nav_menus( array( 'menu-1' => __( 'Primary', 'hello-elementor' ) ) );
		}

		if ( apply_filters( 'hello_elementor_theme_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support( 'custom-logo' );
			add_theme_support( 'html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			) );
			add_theme_support( 'custom-logo', array(
				'height' => 100,
				'width' => 350,
				'flex-height' => true,
				'flex-width' => true,
			) );

			/*
			 * Editor Style
			 */
			add_editor_style( 'editor-style.css' );

			/*
			 * WooCommerce
			 */
			if ( apply_filters( 'hello_elementor_theme_add_woocommerce_support', true ) ) {
				// WooCommerce in general:
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0):
				// zoom:
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox:
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe:
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_theme_setup' );

/*
 * Theme Scripts & Styles
 */
if ( ! function_exists( 'hello_elementor_theme_scripts_styles' ) ) {
	function hello_elementor_theme_scripts_styles() {
		if ( apply_filters( 'hello_elementor_theme_enqueue_style', true ) ) {
			wp_enqueue_style( 'elementor-hello-theme-style', get_stylesheet_uri() );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_theme_scripts_styles' );

/*
 * Register Elementor Locations
 */
if ( ! function_exists( 'hello_elementor_theme_register_elementor_locations' ) ) {
	function hello_elementor_theme_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_theme_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_theme_register_elementor_locations' );


add_action('pre_get_posts','custom_query_vars');
function custom_query_vars($query) {
	$query->set('current_date', date('Y-m-d'));
	return $query;
}

function ppwp_custom_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
		background-image: url(https://alliance.bravefactor.com/wp-content/uploads/2022/08/TheAlliance_Logo_RGB_FullColor.svg);
        height:auto;
        width:250px;
        background-size: contain;
        background-repeat: no-repeat;
        padding-bottom: 10px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'ppwp_custom_login_logo' );

function ppwp_custom_login_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'ppwp_custom_login_url' );
function ppwp_login_logo_url_redirect() {
    return 'https://californiacompetes.org/';
}
add_filter( 'login_headertitle', 'ppwp_login_logo_url_redirect' );

// Alternative
// Fully Disable Gutenberg editor.
add_filter('use_block_editor_for_post_type', '__return_false', 10);
// Don't load Gutenberg-related stylesheets.
add_action( 'wp_enqueue_scripts', 'remove_block_css', 100 );
function remove_block_css() {
wp_dequeue_style( 'wp-block-library' ); // Wordpress core
wp_dequeue_style( 'wp-block-library-theme' ); // Wordpress core
}

// function that runs when shortcode is called
function wpb_demo_shortcode() {
   global $wp_query;
   $not_singular = $wp_query->found_posts > 1 ? 'results' : 'result'; // if found posts is greater than one echo results(plural) else echo result (singular)
   echo $wp_query->found_posts . " $not_singular returned";
}
add_shortcode('result_count', 'wpb_demo_shortcode');


function wpb_shortcode() {
	global $wp_query;
   	$not_singular = $wp_query->found_posts > 1 ? '<span>results</span>' : '<span>result</span>'; // if found posts is greater than one echo results(plural) else echo result (singular)
   	echo $wp_query->found_posts . " $not_singular ";
}
add_shortcode('result_count_resource', 'wpb_shortcode');

add_action( 'admin_print_footer_scripts', 'check_textarea_length' );
function check_textarea_length() {
    ?>
    <script type="text/javascript">
        jQuery( document ).ready( function($) {
           $('.post-type-resources').find('thead .column-writer').text('Author');
        });
    </script>
    <?php
}


function replace_excerpt_with_meta_description($excerpt) {
	$post_id = get_the_ID();
	$meta_wds_metadesc = get_post_meta($post_id, '_wds_metadesc', true);
	$meta_description_resource = get_post_meta($post_id, 'description-resource', true);
	$meta__wds_trimmed_excerpt = get_post_meta($post_id, '_wds_trimmed_excerpt', true);

	echo $meta_description_resource;

	if (!empty($meta_description_resource)) {
    	echo $meta_wds_metadesc;
  	} else {
		echo $meta__wds_trimmed_excerpt;
	}

}
add_shortcode('the_excerpt', 'replace_excerpt_with_meta_description');


/* Redirect url */
function redirect_external_url() {
    $current_url = $_SERVER['REQUEST_URI'];
    if ( $current_url === '/?p=p2p/regions-v2&region=oakland-city' ) {
        wp_redirect( 'https://p2p.californiacompetes.org/p2p/regions' );
        exit;
    } elseif ( $current_url === '/?p=p2p/regions-v2' ) {
        wp_redirect( 'https://p2p.californiacompetes.org/p2p/regions' );
        exit;
    } elseif ($current_url === '/?p=blog/california-competes-launches-cross-sector-career-readiness-pilot'){
		wp_redirect('https://californiacompetes.org/california-competes-launches-cross-sector-career-readiness-pilot-with-investment-from-ecmc-foundation/');
		exit;
	} elseif ($current_url === '/?p=blog'){
		wp_redirect('https://californiacompetes.org/resources/');
		exit;
	} elseif ($current_url === '/?p=publications/opportunity-imbalance') {
		wp_redirect('https://californiacompetes.org/resources/opportunity-imbalance-race-gender-and-californias-education-to-employment-pipeline/');
		exit;
	} elseif ($current_url === '/?p=publications/highered-employer-partnerships') {
		wp_redirect('https://californiacompetes.org/resources/insights-from-the-field-barriers-opportunities-for-building-higher-education-employer-partnerships/');
		exit;
	} elseif ($current_url === '/?p=publications/back-to-college-part-one') {
		wp_redirect('https://californiacompetes.org/resources/back-to-college-part-one-californias-imperative-to-re-engage-adults/');
		exit;
	} elseif ($current_url === '/index.php?p=publications/p3') {
		wp_redirect('https://californiacompetes.org/media/');
	} elseif ($current_url === '/?p=publications/p3'){
		wp_redirect('https://californiacompetes.org/media/');
		exit;
	}
}
add_action( 'template_redirect', 'redirect_external_url' );


/* add superscript to editor */
function my_mce_buttons_2( $buttons ) {
	/**
	 * Add in a core button that's disabled by default
	 */
	$buttons[] = 'superscript';
	$buttons[] = 'subscript';

	return $buttons;
}
add_filter( 'mce_buttons_2', 'my_mce_buttons_2' );

/* add column size to media */
add_filter( 'manage_media_columns', function ( $columns ) {
    $columns['filesize'] = __( 'File Size', 'my-theme-text-domain' );
    return $columns;
} );

add_action( 'manage_media_custom_column', function ( $column_name, $post_id ) {
    if ( $column_name === 'filesize' ) {
        $bytes = filesize( get_attached_file( $post_id ) );
        echo size_format( $bytes, 2 );
    }
}, 10, 2 );

add_action( 'admin_print_styles-upload.php', function () {
    echo '<style>.fixed .column-filesize { width: 10%; }</style>';
} );

/* Order Posts Alphabetically */
function prefix_modify_query_order( $query ) {
  if ( is_admin() && $query->is_main_query() ) {
    $args =  array( 'post_date' => 'DESC', 'title' => 'ASC' );
    $query->set( 'orderby', $args );
  }
}
add_action( 'pre_get_posts', 'prefix_modify_query_order' );

/*Disable Author Schema*/
add_action( 'template_redirect', function () {

    if ( ! is_singular() || ! method_exists( 'Smartcrawl_Settings', 'get_component_options' ) ) {
        return;
    }

    $settings = Smartcrawl_Settings::get_component_options( Smartcrawl_Settings::COMP_SOCIAL );

    if ( empty( $settings['og-enable'] ) ) {
        return;
    }

    ob_start( function( $content ){

        $pattern = '/<meta property="article:author" content="(.*?)" \/>/is';

        if( preg_match( $pattern, $content ) ){
            return preg_replace( $pattern, '', $content );
        }

        return $content;

    } );

});

// This function enqueues the Normalize.css for use. The first parameter is a name for the stylesheet, the second is the URL. Here we
// use an online version of the css file.
function add_normalize_CSS() {
   wp_enqueue_style( 'normalize-styles', "https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css");
}

add_action('wp_enqueue_scripts', 'add_normalize_CSS');

function add_widget_support() {
	register_sidebar( array(
					'name'          => 'Sidebar',
					'id'            => 'sidebar',
					'before_widget' => '<div>',
					'after_widget'  => '</div>',
					'before_title'  => '<h2>',
					'after_title'   => '</h2>',
	) );
}
// Hook the widget initiation and run our function
add_action( 'widgets_init', 'add_widget_support' );

// Register a new navigation menu
function add_Main_Nav() {
	register_nav_menu('header-menu',__( 'Header Menu' ));
  }
  // Hook to the init action hook, run our navigation menu function
  add_action( 'init', 'add_Main_Nav' );