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
class DeleteListboxAction extends ListboxAction {
   
   // Constructor
   public function __construct($element) {
       parent::__construct($element);
   }
   
   /*
    * Perform action to array
    * 
    * @param mixed[] $array Array of elements
    * 
    * @return bool Success or NOT
    */
   public function apply(&$array) {
       foreach ($array as $key => $value) {
           if ($value == $this->applyToElement) {
               unset ($array[$key]);
           }
       }
       
       return TRUE;
   }
   
   /*
    * PRINT
    */
   public function servicePrint() {
       echo "DELETE ".$this->applyToElement;
   }
   
}
