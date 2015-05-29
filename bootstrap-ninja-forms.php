<?php
/*
Plugin Name: Bootstrap Classes for Ninja Forms
Plugin URI: https://github.com/bostondv/bootstrap-ninja-forms
Description: Adds Bootstrap classes to Ninja Forms
Version: 1.0.0
Author: bostondv
Author URI: http://pomelodesign.com
Text Domain: bs-ninja-forms
Domain Path: /languages/
License: GPLv3
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

class Ninja_Forms_Bootstrap_Classes {

  /**
   * Setup the plugin
   *
   * @author Boston Dell-Vandenberg
   * @since  1.0.0
   */
  public static function setup() {
    if ( ! self::is_ninjaform_installed() ) {
      return;
    }
    $class = __CLASS__;
    new $class;
  }

  /**
   * Add necessary hooks and filters functions
   *
   * @author Boston Dell-Vandenberg
   * @since  1.0.0
   */
  function __construct() {
    add_filter( 'ninja_forms_field_wrap_class', array( $this, 'forms_field_wrap_class' ), 10, 2 );
    add_action( 'ninja_forms_field', array( $this, 'forms_field' ), 10, 2 );
    add_action( 'ninja_forms_display_field_label', array( $this, 'forms_display_field_label' ), 10, 2 );
  }

  function forms_field_wrap_class( $field_wrap_class, $field_id ) {
    global $ninja_forms_loading;
    $settings = $ninja_forms_loading->get_field_settings( $field_id );

    if ( $settings['type'] === '_checkbox' ) {
      $field_wrap_class .= ' form-group checkbox-inline';
    } else {
      $field_wrap_class .= ' form-group';
    }

    return $field_wrap_class;
  }

  function forms_display_field_label( $field_id, $data ) {
    return $data;
  }

  function forms_field( $data, $field_id ) {
    global $ninja_forms_loading;
    $settings = $ninja_forms_loading->get_field_settings( $field_id );

    if ( $settings['type'] === '_text' ||
         $settings['type'] === '_textarea' ||
         $settings['type'] === '_profile_pass' ||
         $settings['type'] === '_spam' ||
         $settings['type'] === '_number' ||
         $settings['type'] === '_country' ||
         $settings['type'] === '_tax' ||
         $settings['type'] === '_calc' ) {
      $data['class'] .= ' form-control';
    }

    if ( $settings['type'] === '_list' ) {
      if ( $settings['data']['list_type'] !== 'checkbox' && $settings['data']['list_type'] !== 'radio' ) {
        $data['class'] .= ' form-control';
      }
    }

    if ( $settings['type'] === '_submit' ) {
      $data['class'] .= ' btn btn-primary';
    }

    if ( $settings['type'] === '_checkbox' ) {
      $data['label_pos'] = 'below';
    }

    return $data;
  }

  /**
   * Check if Ninja Forms is installed
   *
   * @author Boston Dell-Vandenberg
   * @since  1.0.0
   */
  private static function is_ninjaform_installed() {
    return defined( 'NINJA_FORMS_VERSION' );
  }

}

add_action( 'plugins_loaded', array( 'Ninja_Forms_Bootstrap_Classes', 'setup' ) );
