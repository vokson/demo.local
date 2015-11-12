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
class BoolValue extends \Classes\Value\Value {

   /*
    * Validate if input value class is Boolean
    * 
    * @param mixed $value Input value
    * @return bool
    */
    public function validateValueType(&$value) {
        return settype($value, 'bool');
   }
}
