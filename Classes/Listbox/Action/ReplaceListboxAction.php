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
class ReplaceListboxAction extends ListboxAction {
    private $arrayOfNewElements;

    // Constructor
   public function __construct($element, $arrayOfNewElements) {
       parent::__construct($element);
       $this->arrayOfNewElements = $arrayOfNewElements;
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
       
       foreach ($this->arrayOfNewElements as $element) {
           $listbox->add($element);
       }
       
       return TRUE;
   }
   
   /*
    * PRINT
    */
   public function servicePrint() {
       echo "REPLACE ".$this->applyToElement. " WITH ". implode(",", $this->arrayOfNewElements);
   }
   
}
