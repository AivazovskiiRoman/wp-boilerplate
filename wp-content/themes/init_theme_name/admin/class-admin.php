<?php
/**
 * The Admin specific functionality.
 * General stuff that is not specific to any class.
 *
 * @since   1.0.0
 * @package init_theme_name
 */

namespace Inf_Theme\Admin;

/**
 * Class Admin
 */
class Admin {

  /**
   * Global theme name
   *
   * @var string
   *
   * @since 1.0.0
   */
  protected $theme_name;

  /**
   * Global theme version
   *
   * @var string
   *
   * @since 1.0.0
   */
  protected $theme_version;

  /**
   * Initialize class
   *
   * @param array $theme_info Load global theme info.
   *
   * @since 1.0.0
   */
  public function __construct( $theme_info = null ) {
    $this->theme_name     = $theme_info['theme_name'];
    $this->theme_version  = $theme_info['theme_version'];
  }

  /**
   * Register the Stylesheets for the admin area.
   *
   * @since 1.0.0
   */
  public function enqueue_styles() {

    $main_style = '/skin/public/styles/applicationAdmin.css';
    wp_register_style( $this->theme_name . '-style', get_template_directory_uri() . $main_style, array(), $this->general_helper->get_assets_version( $main_style ) );
    wp_enqueue_style( $this->theme_name . '-style' );

  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since 1.0.0
   */
  public function enqueue_scripts() {

    $main_script = '/skin/public/scripts/applicationAdmin.js';
    wp_register_script( $this->theme_name . '-scripts', get_template_directory_uri() . $main_script, array(), $this->general_helper->get_assets_version( $main_script ) );
    wp_enqueue_script( $this->theme_name . '-scripts' );

  }

  /**
   * Add admin bar class for different environment
   *
   * You can style admin bar of each environment differently for better
   * differentiation, and smaller chance of error.
   *
   * @param  string $classes Get preset body classes.
   * @return string $classes Body classes with env class.
   *
   * @since 1.0.0
   */
  function set_enviroment_body_class( $classes ) {
    $this->env = '';

    if ( defined( 'INF_ENV' ) ) {
      $this->env = INF_ENV;
    }

    $classes .= ' env--' . $this->env;

    return $classes;
  }

}
