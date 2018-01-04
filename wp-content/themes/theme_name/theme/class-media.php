<?php

/**
 * The login-specific functionality.
 * 
 * @since      1.0.0
 *
 * @package    Aaa
 */

/**
 * The login-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Aaa
 */
namespace Inf_Theme\Theme;

class Media {

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
   * Enable theme support
   *
   * @return void
   */
  public function add_theme_support() {
    add_theme_support( 'post-thumbnails' );
  }

  public function add_custom_image_sizes() {
    add_image_size( 'full_width', 1440, 9999, true );
    add_image_size( 'listing', 570, 320, true );
  }

  /**
   * Enable SVG uplod in media
   *
   * @param array $mimes Load all mimes.
   * @return array
   */
  public function enable_mime_types( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['zip'] = 'application/zip';
    return $mimes;
  }

  /**
  * Enable SVG preview in Media Library
  *
  * @param array      $response   Array of prepared attachment data.
  * @param int|object $attachment Attachment ID or object.
  * @param array      $meta       Array of attachment meta data.
  */
  public function enable_svg_library_preview( $response, $attachment, $meta ) {
    if ( $response['type'] === 'image' && $response['subtype'] === 'svg+xml' && class_exists( 'SimpleXMLElement' ) ) {
      try {
        $path = get_attached_file( $attachment->ID );

        if ( file_exists( $path ) ) {
            $svg = new \SimpleXMLElement( file_get_contents( $path ) );
            $src = $response['url'];
            $width  = (int) $svg['width'];
            $height = (int) $svg['height'];

            // media gallery.
            $response['image'] = compact( 'src', 'width', 'height' );
            $response['thumb'] = compact( 'src', 'width', 'height' );

            // media single.
            $response['sizes']['full'] = array(
                'height'      => $height,
                'width'       => $width,
                'url'         => $src,
                'orientation' => $height > $width ? 'portrait' : 'landscape',
            );
        }
      } catch ( Exception $e ) {
        new \WP_Error( esc_html__( 'Error: ', 'hpb' ) . $e );
      }
    }

    return $response;
  }

  /**
   * Wrap utility class arround iframe to enable responsive
   *
   * @param  string $html Iframe html to wrap around.
   * @return  string Wrapped iframe with a utility class.
   */
  public function wrap_responsive_oembed_filter( $html ) {
    $return = '<span class="iframe u__embed-video-responsive">' . $html . '</span>';
    return $return;
  }
}
