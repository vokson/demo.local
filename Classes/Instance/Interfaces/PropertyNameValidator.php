<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Instance\Interfaces;

/**
 *
 * @author Noskov Alexey
 */
interface PropertyNameValidator {
   
   /*
     * Validate name of property
     * 
     * @param string $propertyName name of property
     * 
     * @return bool
     */
    function validateProperty($propertyName); 
}
