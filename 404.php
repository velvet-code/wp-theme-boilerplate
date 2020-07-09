<?php
/**
 * Template for 404 page
 *
 * @package boilerplate
 */

get_header();
?>

  <div class="container flex flex-col justify-center text-center">
    <div class="text-6xl font-bold">404</div>
    <div><?php _e('Page not found', 'boilerplate'); ?></div>
  </div>

<?php get_footer(); ?>
