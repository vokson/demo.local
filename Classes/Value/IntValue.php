<?php

namespace Classes\Value;

/**
 * Class to keep Float values
 *
 * @author Noskov Alexey
 */
class IntValue extends \Classes\Value\NumericValue {

   /*
    * Validate if input value class is Float
    * 
    * @param mixed $value Input value
    * @return bool
    */
    public function validateValueType($value) {
       return is_int($value);
   }
}
