<?php
/*
Plugin Name: Bootstrap Classes for Ninja Forms
Plugin URI: https://github.com/bostondv/bootstrap-ninja-forms
Description: Adds Bootstrap classes to Ninja Forms
Version: 1.0.2
Author: bostondv
Author URI: http://pomelodesign.com
Text Domain: bs-ninja-forms
Domain Path: /languages/
License: MIT
License URI: http://opensource.org/licenses/MIT
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
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10);
    add_filter( 'ninja_forms_display_field_wrap_class', array( $this, 'forms_field_wrap_class' ), 10, 2 );
    add_action( 'ninja_forms_field', array( $this, 'forms_field' ), 10, 2 );
    add_filter( 'ninja_forms_form_class', array( $this, 'forms_form_class' ), 10, 2 );
    add_filter( 'ninja_forms_form_wrap_class', array( $this, 'forms_form_wrap_class' ), 10, 2 );
    add_action( 'ninja_forms_label_class', array( $this, 'forms_label_class' ), 10, 2 );
  }

  /**
   * Loads custom css classes to help adapt to Boostrap
   *
   * @author Boston Dell-Vandenberg
   * @since  1.0.0
   */
  function enqueue_scripts() {
    wp_register_style( 'bootstrap_ninja_forms_styles', plugins_url( 'bootstrap-ninja-forms.css', __FILE__ ) );
    wp_register_script( 'bootstrap_ninja_forms_scripts', plugins_url( 'bootstrap-ninja-forms.js', __FILE__ ), array( 'jquery' ), false, true );

    if ( apply_filters( 'bootstrap_ninja_forms_load_styles', true ) ) {
      wp_enqueue_style( 'bootstrap_ninja_forms_styles' );
    }
    
    if ( apply_filters( 'bootstrap_ninja_forms_load_scripts', true ) ) {
      wp_enqueue_script( 'bootstrap_ninja_forms_scripts' );
    }
  }

  /**
   * Modifies form wrap classes
   *
   * @author Boston Dell-Vandenberg
   * @since  1.0.0
   */
  function forms_form_wrap_class( $wrap_class, $form_id ) {
    $wrap_class = apply_filters( 'bootstrap_ninja_forms_form_wrap_class', '-bootstrap' );
    return $wrap_class;
  }

  /**
   * Modifies form element classes
   *
   * @author Boston Dell-Vandenberg
   * @since  1.0.0
   */
  function forms_form_class( $form_class, $form_id ) {
    $form_class = apply_filters( 'bootstrap_ninja_forms_form_class', 'ninja-forms-bootstrap' );
    return $form_class;
  }

  /**
   * Modifies form label classes
   *
   * @author Boston Dell-Vandenberg
   * @since  1.0.1
   */
  function forms_label_class( $label_class, $field_id ) {
    $label_class .= ' control-label';
    return $label_class;
  }

  /**
   * Modifies field wrap classes
   *
   * @author Boston Dell-Vandenberg
   * @since  1.0.0
   */
  function forms_field_wrap_class( $field_wrap_class, $field_id ) {
    $settings = $this->get_field_settings( $field_id );

    $field_wrap_class = str_replace('field-wrap', 'form-group', $field_wrap_class);
    $field_wrap_class = str_replace('ninja-forms-error', 'has-error', $field_wrap_class);

    return $field_wrap_class;
  }

  /**
   * Modifies form field classes
   *
   * @author Boston Dell-Vandenberg
   * @since  1.0.0
   */
  function forms_field( $data, $field_id ) {
    $settings = $this->get_field_settings( $field_id );

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
   * Gets field settings for specified field ID
   *
   * @author Boston Dell-Vandenberg
   * @since 1.0.1
   */
  private function get_field_settings( $field_id ) {
    global $ninja_forms_loading;
    global $ninja_forms_processing;

    if ( isset ( $ninja_forms_loading ) ) {
      $field_row = $ninja_forms_loading->get_field_settings( $field_id );
    } else if ( isset ( $ninja_forms_processing ) ) {
      $field_row = $ninja_forms_processing->get_field_settings( $field_id );
    }

    return $field_row;
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
