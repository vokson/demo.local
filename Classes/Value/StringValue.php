<?php

namespace Classes\Value;

/**
 * Class to keep String values
 *
 * @author Noskov Alexey
 */
class StringValue extends \Classes\Value\Value {

   /*
    * Validate if input value class is String
    * 
    * @param mixed $value Input value
    * @return bool
    */
    public function validateValueType($value) {
       return is_string($value);
   }
}
