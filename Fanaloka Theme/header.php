<?php
/**
 * The template for displaying the header.
 *
 * This is the template that displays all of the <head> section, opens the <body> tag and adds the site's header
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
   <title><?php bloginfo('name'); ?> &raquo; <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
   <meta charset="<?php bloginfo( 'charset' ); ?>">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
   <?php wp_head(); ?>

   <header class="my-logo">
    <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/Images/logo.png" alt="Site Logo" width="50px" height="50px"></a></h1>
	</header>
	<?php wp_nav_menu( array( 'header-menu' => 'header-menu' ) ); ?>
 </head>
<body <?php body_class(); ?>>

<?php
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
	get_template_part( 'template-parts/header' );
}
