<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Instance;

/**
 * Base class to keep instances, such nodes, lines, triangles..
 *
 * @author Noskov Alexey
 */
abstract class Instance implements Interfaces\PropertyNameValidator {
    /*
     * @param string UIN
     */
    protected $uin = NULL;
    /*
     * @param Value[] associative array of properties
     */
    protected $properties = array(); 
    
    // Service print function
    abstract public function servicePrint();
    
    /*
     * Construct new objct of class
     * 
     * @return void
     */
    function  __construct() {
        $this->uin = uniqid('', TRUE);
    }
    
    /*
     * Clone of object
     */
    function __clone() {
        $this->uin = uniqid('', TRUE);
    }
    
    /*
     * Refresh Uin
     * It's necessary when you want to copy Instance
     */
//    public function newUin() {
//        $this->uin = uniqid('', TRUE);
//    }
//    
    
    
    /*
     * Get UIN
     * 
     * @return string current uin
     */
    public function getUin() {
        return $this->uin;
    }
    
    /*
     * Get property 
     * 
     * @param string $propertyName name of property
     * 
     * @throws \Classes\Exception\Instance\InvalidArgumentException if property
     * is empty or type isn't correct
     * 
     * @return Value if property exists
     * @return NULL if property doesn't exist
     */
    public function getProperty($propertyName) {
        
        if ($this->validateProperty($propertyName)) {
            return $this->properties[$propertyName];
        } else {
            return NULL;
        }
    }
    
    /*
     * Set property if exists, add if there isn't
     * 
     * @param string $propertyName name of property
     * @param Value $value value to be setted
     * 
     * @throws \Classes\Exception\Instance\InvalidArgumentException if properties
     * are empty or type isn't correct
     * @return void
     */
    public function setProperty($propertyName, $value) {
//        echo "Instance Class: propertyName = ". $propertyName. "; value = ".$value->get()."<br/>";
        
        $this->validatePropertyNameType($propertyName);
        
        if (!$this->validateProperty($propertyName)) {
            throw new \Classes\Exception\Instance\InvalidPropertyNameException ('Property Name does NOT exist');
        }
        
        if (!($value instanceof \Classes\Value\Value)) {
            throw new \Classes\Exception\Instance\InvalidArgumentException ('Invalid Value argument');
        }
        
        $this->properties[$propertyName] = $value;
    }
    
    /*
     * Validate name of property
     * 
     * @param string $propertyName name of property
     * 
     * @return bool
     */
    public function validateProperty($propertyName, &$propertyClass = NULL) {
        
        $this->validatePropertyNameType($propertyName);
        
//        var_dump($this->validPropertyNames);

        if (isset($this->validPropertyNames[$propertyName])) {
            $propertyClass = $this->validPropertyNames[$propertyName];
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Validate type of property's name
     * 
     * @param string $propertyName name of property
     * 
     * @throws \Classes\Exception\Instance\InvalidArgumentException if property
     * is empty or type isn't correct
     * 
     * @return bool
     */
    public function validatePropertyNameType($propertyName) {
        if (!is_string($propertyName)) {
            throw new \Classes\Exception\Instance\InvalidArgumentException ('Invalid Property Name argument');
        }
        return TRUE;
    }
    
}
