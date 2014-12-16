<?php

class Validator {

  protected $errorHandler;
  protected $items;
  protected $laws = array( 'required', 'minlength', 'maxlength', 'email', 'alphanumeric', 'match' );

  public $rules;
  public $error_messages = array(
    'required'     => 'The :field field is required',
    'minlength'    => 'The :field field must be a minumum of :satisfier characters',
    'maxlength'    => 'The :field field must be a maximum of :satisfier characters',
    'email'        => 'Please provide a valid email address',
    'alphanumeric' => 'The :field field must contain only letters and numbers',
    'match'        => 'The :field field must match the :satisfier field'
  );


  public function __construct( ErrorHandler $errorHandler ) {

    $this->errorHandler = $errorHandler;

  }


  public function check( $items, $rules = null ) {

    $this->items = $items;

    if ( !isset( $rules ) ) {
      $rules = $this->rules;
    }
    
    foreach ( $items as $item => $value ) {

      // array_keys of $rules are in the $item array
      if ( in_array( $item, array_keys( $rules ) ) ) {
        
        $item = array(
            'field'  => $item,
            'value'  => $value,
            'laws'   => $rules[$item]
          );

        $this->validate( $item );
      }

    }

    return $this;
    
  }

  public function fails() {
    return $this->errorHandler->hasErrors();
  }


  public function errors() {
    return $this->errorHandler;
  }


  protected function validate( $item ) {

    $field = $item['field'];

    foreach ( $item['laws'] as $law => $satisfier ) {
      
      if ( in_array( $law, $this->laws ) ) {
        
        // Dynamically call the necessary method based on the $law name
        if ( !call_user_func_array( array( $this, $law ), array( $field, $item['value'], $satisfier ) ) ) {
          $this->errorHandler->addError(  str_replace( array( ':field', ':satisfier' ), array( $field, $satisfier ), $this->error_messages[$law] ), $field );

        }
     
      }

    }

  }


  protected function required( $field, $value, $satisfier ) {

    // Won't work on php < 5.5 ...stupid php!
    // return !empty( trim( $value ) );

    $trimmed_value = trim( $value );

    return !empty( $trimmed_value );

  }


  protected function minlength( $field, $value, $satisfier ) {

    return mb_strlen( $value ) >= $satisfier;

  }


  protected function maxlength( $field, $value, $satisfier ) {

    return mb_strlen( $value ) <= $satisfier;

  }


  protected function email( $field, $value, $satisfier ) {

    return filter_var( $value, FILTER_VALIDATE_EMAIL );

  }


  protected function alphanumeric( $field, $value, $satisfier ) {

    return ctype_alnum( $value );

  }

  protected function match( $field, $value, $satisfier ) {

    return $value === $this->items[$satisfier];

  }

}