<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Listbox\Action;

/**
 * Action of Listbox
 *
 * @author Noskov Alexey
 */
abstract class ListboxAction {
   // Action to be applied to element
   protected $applyToElement;
   
   // Constructor
   public function __construct($element) {
       $this->applyToElement = $element;
   }
   
   /*
    * Get Target Element
    * 
    * @return string
    * @return int
    */
   public function getTarget() {
       return $this->applyToElement;
   }
   
   /*
    * Perform action to array
    * 
    * @param mixed[] $array Array of elements
    * 
    * @return bool Success or NOT
    */
   abstract public function apply(&$array);
   
   /*
    * PRINT
    */
   abstract public function servicePrint();
   
}
