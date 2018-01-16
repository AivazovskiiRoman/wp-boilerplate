<?php
/**
 * The Advance Custom Fields general helper specific functionality.
 * Used on fields created via ACF for theme options
 *
 * @since   1.0.0
 * @package theme_name
 */

namespace Inf_Theme\Theme\Acf;

use Inf_Theme\Helpers as General_Helpers;

/**
 * Class Theme Options General
 */
class Theme_Options_General {

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
   * Global assets version
   *
   * @var string
   *
   * @since 1.0.0
   */
  protected $assets_version;

  /**
   * Theme options page slug
   *
   * @var string
   *
   * @since 1.0.0
   */
  protected $options_page_slug = 'theme-options-general';

  /**
   * Theme options transient name
   *
   * @var string
   *
   * @since 1.0.0
   */
  public $options_transient_cache_name = 'theme-options-general';

  /**
   * Initialize class
   *
   * @param array $theme_info Load global theme info.
   */
  public function __construct( $theme_info = null ) {
    $this->theme_name = $theme_info['theme_name'];
    $this->theme_version = $theme_info['theme_version'];
    $this->assets_version = $theme_info['assets_version'];
  }

  /**
   * Create Options page in sidebar
   *
   * @since 1.0.0
   */
  public function create_theme_options_page() {
    if ( function_exists( 'acf_add_options_page' ) ) {

      acf_add_options_page(
        array(
            'page_title'    => 'General Settings',
            'menu_title'    => 'Theme Options',
            'menu_slug'     => $this->options_page_slug,
            'capability'    => 'edit_theme_options',
            'redirect'      => false,
        )
      );
    }
  }

  /**
   * Populate Options page
   *
   * @since 1.0.0
   */
  public function register_theme_options() {
    if ( function_exists( 'acf_add_options_page' ) ) {
      acf_add_local_field_group(
        array(
            'key' => 'group_59b6769d4340b',
            'title' => 'Theme Options General',
            'fields' => array(
                array(
                    'key' => 'field_59cd1cc201a30',
                    'label' => 'Pages',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_59cd1ca001a22',
                    'label' => 'Old Browsers',
                    'name' => 'old_browsers_page',
                    'type' => 'post_object',
                    'instructions' => 'Select Old Browsers page',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                        0 => 'page',
                    ),
                    'taxonomy' => array(),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'return_format' => 'id',
                    'ui' => 1,
                ),
                array(
                    'key' => 'field_59b676a3281da',
                    'label' => 'Footer',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_59b676ac281db',
                    'label' => 'Footer Copyright Content',
                    'name' => 'footer_copyright_content',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_59bf87b3b2e64',
                    'label' => 'Advanced',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_59bf87e6b2e65',
                    'label' => 'Google Maps API Key',
                    'name' => 'google_maps_api_key',
                    'type' => 'text',
                    'instructions' => 'https://developers.google.com/maps/documentation/javascript/get-api-key',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5a37b9f677ba4',
                    'label' => 'REST API Endpoints',
                    'name' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'glossary_support' => 0,
                    'message' => 'Check Endpoints if they are successfuly saved in transient! If there is an error open the page on correct language and it will be updated.',
                    'new_lines' => 'br',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_59b676a3281d1',
                    'label' => 'Cookies Notification',
                    'name' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_59fb504743aa4',
                    'label' => 'Content',
                    'name' => 'cookies_notification_description',
                    'type' => 'wysiwyg',
                    'value' => null,
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'tabs' => 'all',
                    'toolbar' => 'full',
                    'media_upload' => 0,
                    'delay' => 1,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => $this->options_page_slug,
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        )
      );
    }
  }

  /**
   * Get Theme options data to array from DB
   * You should never use thos function to get data but you should use transient version
   *
   * @return array Array containing theme options.
   *
   * @since 1.0.0
   */
  private function get_theme_options_data() {
    return array(
        'old_browsers_page'                   => get_field( 'old_browsers_page', 'options' ),
        'footer_copyright_content'            => get_field( 'footer_copyright_content', 'options' ),
        'google_maps_api_key'                 => get_field( 'google_maps_api_key', 'options' ),
        'cookies_notification_description'    => get_field( 'cookies_notification_description', 'options' ),
    );
  }

  /**
   * Get Theme options
   *
   * This is a helper function that will get all the options from the ACF and
   * store them in a transient, so that only one call to the DB will be made when
   * fetching theme options.
   *
   * @return array Array containing theme options from transient.
   *
   * @since 1.0.0
   */
  private function get_theme_options() {
    $cache_name = $this->options_transient_cache_name;
    $cache = get_transient( $cache_name );

    if ( $cache === false ) {
      $cache = $this->get_theme_options_data();

      set_transient( $cache_name, $cache, 0 );
    }

    return $cache;
  }

  /**
   * Get single key from options array
   *
   * @param string $key Theme option name.
   * @return mixed      Function that will return the array of the key.
   *
   * @since 1.0.0
   */
  public function get_theme_option( $key ) {
    global $inf_theme_options;
    $this->general_helper = new General_Helpers\General_Helper();

    return $this->general_helper->get_array_value( $key, $inf_theme_options );
  }

  /**
   * Register global variable for theme options
   * When getting options from admin you should always get it from global variable
   *
   * @since 1.0.0
   */
  public function register_global_theme_options_variable() {
    global $inf_theme_options;

    $inf_theme_options = $this->get_theme_options();
  }

  /**
   * Check if is theme options page
   *
   * @return boolian True/False if is theme options page.
   *
   * @since 1.0.0
   */
  public function is_theme_options_page() {
    $screen = get_current_screen();

    if ( is_admin() && ( $screen->id === 'toplevel_page_' . $this->options_page_slug ) ) {
      return true;
    }

    return false;
  }

  /**
   * Delete transient for theme options
   *
   * @since 1.0.0
   */
  public function delete_theme_options_transient() {
    if ( $this->is_theme_options_page() === true ) {
      $cache_name = $this->options_transient_cache_name;
      delete_transient( $cache_name );
    }
  }

}
