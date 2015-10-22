<?php

namespace Classes\Value;

/**
 * Class to keep numeric values, which can be added / substracted
 *
 * @author Noskov Alexey
 */
abstract class NumericValue extends \Classes\Value\Value {

    /*
     * Function add value to current value
     * 
     * @param mixed $addValue Additive value
     * @return void
     */
   public function add($addValue) {
       //Check value
       $this->validateValue($addValue);
       
           $this->value += $addValue;
   }
}
