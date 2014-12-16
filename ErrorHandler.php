<?php
/**
 * Error Handler class
 * 
 * @version 2.0
 * @since 1.0
 */
class ErrorHandler {

  protected $errors = array();


  /**
   * Add error message
   *
   * @param  string $error Error message to add to array. (Required)
   * @param  array  $key    Key to add error to. (Optional)
   * @return void
   *
   * @since 1.0.0
   */
  public function addError( $error, $key = null ) {

    if ( $key ) {
      $this->errors[$key][] = $error;
    }
    else {
      $this->error[] = $error;
    }

  }


  /**
   * Check if there are errors
   *
   * @return boolean If has errors true, otherwise false
   *
   * @since 1.0.0
   */
  public function hasErrors() {
    return count( $this->all() ) ? true : false;
  }


  /**
   * Return all errors or by key
   *
   * @param  string $key  Field to return errors for. (Optional)
   * @return array        Array of errors
   *
   * @since 1.0.0
   */
  public function all( $key = null ) {
    // Prevent undefined index error...
    if (isset($key)) $errors_key = $this->errors[$key];


    // If a key is present in the ErrorHandler::errors array return that, otherwise return the entire array
    $result = isset( $errors_key ) ? $this->errors[$key] : $this->errors;
    return $result;

  }


  /**
   * Get the first error for the specified field
   *
   * @param  string $key Name of field to return first error from. (Required)
   * @return string      The first error for the specified field
   *
   * @since 1.0.0
   */
  public function first( $key ) {

    $all = $this->all();
    $all_key = $all[$key][0];

    return isset( $all_key ) ? $all_key : '' ;

  }

}