<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

 if ( is_active_sidebar( 'sidebar' ) ) : ?>
	<aside id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
	  <?php dynamic_sidebar( 'sidebar' ); ?>
	</aside>
<?php endif; ?>

/**
 * This file is here to avoid the Deprecated Message for sidebar by wp-includes/theme-compat/sidebar.php
 *
 */
