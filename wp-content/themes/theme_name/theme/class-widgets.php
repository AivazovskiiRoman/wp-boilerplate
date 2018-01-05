<?php

/**
 * The theme-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package theme_name
 */

/**
 * The theme-specific functionality of the plugin.
 *
 * Defines the theme name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    theme_name
 */
namespace Inf_Theme\Theme;

class Widgets {

  protected $theme_name;

  protected $theme_version;

  protected $assets_version;

  /**
   * Init call
   */
  public function __construct( $theme_info = null ) {
    $this->theme_name = $theme_info['theme_name'];
    $this->theme_version = $theme_info['theme_version'];
    $this->assets_version = $theme_info['assets_version'];
  }

  /**
   * Set up widget areas
   *
   * @package theme_name
   */
  public function register_widget_position() {
    register_sidebar(
      array(
      'name'          => esc_html__( 'Blog', 'theme_name' ),
      'id'            => 'blog',
      'description'   => esc_html__( 'Description', 'theme_name' ),
      'before_widget' => '',
      'after_widget'  => '',
      'before_title'  => '',
      'after_title'   => '',
      )
    );
  }

}
