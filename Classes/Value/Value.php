<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Value;

/**
 * Base class to keep values inside
 *
 * @author Noskov Alexey
 */
abstract class Value {
    protected $value;
    
    /*
     * Construct new object of class
     * 
     * @param mixed $value Input Value
     */
    function __construct($value) {
        //Set value
        $this->set($value);
    }

    /*
     * Validate value
     * 
     * @param mixed $value Input Value
     * 
     * @throws \Classes\Exception\Value\EmptyArgumentException if empty 
     * value passed to function
     * 
     * @throws \Classes\Exception\Value\InvalidArgumentException if type 
     * of value is wrong
     * 
     * @return void
     */
    protected function validateValue(&$value) {
        //Check isset or not
        if (!isset($value)) {
            throw new \Classes\Exception\Value\EmptyArgumentException('Value is empty');
        }
        //Check type
        if (!$this->validateValueType($value)) {
            
            throw new \Classes\Exception\Value\InvalidArgumentException('Type of value is invalid');
        }
    }
    
    /*
     * Validate value type
     * 
     * @param mixed $value Input Value
     */
    abstract function validateValueType(&$value);
    
    /*
     * Get value
     * 
     * @return mixed Current value
     */
    public function get() {
        return $this->value;
    }
    
    /*
     * Set value
     * 
     * @param mixed Input Value
     * @return void
     */
    public function set($value) {
       //Check value type
        $this->validateValue($value);

        //Set value
        $this->value = $value;
    }
    
}
