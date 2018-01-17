<?php
/**
 * Theme Name: theme_name
 * Description: description
 * Author: author
 * Author URI: author_uri
 * Version: version
 *
 * @package theme_name
 */

namespace Inf_Theme;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * Theme version global
 *
 * @package theme_name
 */

define( 'INF_THEME_VERSION', '1.0.0' );

/**
 * Theme name global
 *
 * @package theme_name
 */

define( 'INF_THEME_NAME', 'theme_name' );

/**
 * Change every time you deploy changes in assets to the server
 * to force browser to clear cache
 *
 * @package theme_name
 */

define( 'INF_ASSETS_VERSION', '1.0.0' );

/**
 * Global image path
 *
 * @package theme_name
 */
define( 'INF_IMAGE_URL', get_template_directory_uri() . '/skin/public/images/' );

/**
 * Include the autoloader so we can dynamically include the rest of the classes.
 *
 * @package theme_name
 */
include_once( 'lib/autoloader.php' );

/**
 * Begins execution of the theme.
 *
 * Since everything within the theme is registered via hooks,
 * then kicking off the theme from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function init_theme() {
  $plugin = new Includes\Main();
  $plugin->run();
}

init_theme();
