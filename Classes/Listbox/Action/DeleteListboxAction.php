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
    * @param \Classes\Listbox\Listbox $listbox Listbox
    * 
    * @return bool Success or NOT
    */
   public function apply(&$listbox) {
       $listbox->remove($this->applyToElement);
       return TRUE;
   }
   
   /*
    * PRINT
    */
   public function servicePrint() {
       echo "DELETE ".$this->applyToElement;
   }
   
}
