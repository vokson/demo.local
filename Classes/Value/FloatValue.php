<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Value;

/**
 * Class to keep Float values
 *
 * @author Noskov Alexey
 */
class FloatValue extends \Classes\Value\NumericValue {

   /*
    * Validate if input value class is Float
    * 
    * @param mixed $value Input value
    * @return bool
    */
    public function validateValueType($value) {
       return is_float($value);
   }
}
