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
    if ( apply_filters( 'bootstrap_ninja_forms_load_styles', true ) ) {
      add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10);
    }

    add_filter( 'ninja_forms_field_wrap_class', array( $this, 'forms_field_wrap_class' ), 10, 2 );
    add_action( 'ninja_forms_field', array( $this, 'forms_field' ), 10, 2 );
    add_filter( 'ninja_forms_form_class', array( $this, 'forms_form_class' ), 10, 2 );
    add_filter( 'ninja_forms_form_wrap_class', array( $this, 'forms_form_wrap_class' ), 10, 2 );
  }

  /**
   * Loads custom css classes to help adapt to Boostrap
   *
   * @author Boston Dell-Vandenberg
   * @since  1.0.0
   */
  function enqueue_scripts() {
    wp_register_style( 'bootstrap_ninja_forms_styles', plugins_url( 'bootstrap-ninja-forms.css', __FILE__ ) );
    wp_enqueue_style( 'bootstrap_ninja_forms_styles' );
  }

  /**
   * Modifies form wrap class
   *
   * @author Boston Dell-Vandenberg
   * @since  1.0.0
   */
  function forms_form_wrap_class( $wrap_class, $form_id ) {
    $wrap_class = apply_filters( 'bootstrap_ninja_forms_form_wrap_class', '-bootstrap' );
    return $wrap_class;
  }

  /**
   * Adds class to form element
   *
   * @author Boston Dell-Vandenberg
   * @since  1.0.0
   */
  function forms_form_class( $form_class, $form_id ) {
    $form_class = apply_filters( 'bootstrap_ninja_forms_form_class', 'ninja-forms-bootstrap' );
    return $form_class;
  }

  /**
   * Adds class to field wrap elements
   *
   * @author Boston Dell-Vandenberg
   * @since  1.0.0
   */
  function forms_field_wrap_class( $field_wrap_class, $field_id ) {
    global $ninja_forms_loading;
    $settings = $ninja_forms_loading->get_field_settings( $field_id );

    $field_wrap_class = str_replace('field-wrap', 'form-group', $field_wrap_class);

    return $field_wrap_class;
  }

  /**
   * Changes form field classes
   *
   * @author Boston Dell-Vandenberg
   * @since  1.0.0
   */
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

    if ( $settings['type'] === '_desc' ) {
      $data['class'] = 'form-group';
    }

    if ( $settings['type'] === '_list' ) {
      if ( $settings['data']['list_type'] !== 'checkbox' && $settings['data']['list_type'] !== 'radio' ) {
        $data['class'] .= ' form-control';
      }
    }

    if ( $settings['type'] === '_submit' ) {
      $data['class'] .= ' btn btn-primary';
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
