<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Listbox;

/**
 * Description of Listbox
 *
 * @author Noskov Alexey
 */
class Listbox {

    private $array = array(); // Array of elements
    private $uin = NULL; //UIN
    private $listboxActionCollection; //Link to collection

    /*
     * Construct new object of class
     * 
     * @param \Classes\Listbox\Collection\ListboxActionCollection $listboxActionCollection
     * 
     * @return void
     */

    function __construct(&$listboxActionCollection) {
        $this->uin = uniqid('', TRUE);
        $this->listboxActionCollection = $listboxActionCollection;
        
        // Register listbox
        $this->listboxActionCollection->registerListbox($this);
        
    }

    /*
     * Clone of object
     */

    function __clone() {
        $this->uin = uniqid('', TRUE);
    }

    /*
     * Get UIN
     * 
     * @return string current uin
     */

    public function getUin() {
        return $this->uin;
    }

    /*
     * Add element to Listbox
     * 
     * @param mixed[] $element Element to be added
     * 
     * @return bool Success or NOT
     */

    function add($element) {
        if (!$this->isElementTypeCorrect($element)) {
            return FALSE;
        }

        $this->array[$element] = $element;
        return TRUE;
    }

    /*
     * Remove element from Listbox
     * 
     * @param mixed[] $element Element to be removed
     * 
     * @return bool Success or NOT
     */

    function remove($element) {
        if (!$this->isElementTypeCorrect($element)) {
            return FALSE;
        }

        if (isset($this->array[$element])) {
            unset($this->array[$element]);
        }
        return TRUE;
    }

    /*
     * Get Listbox
     * 
     * @return mixed[] Array of elements
     * @return bool FALSE if no success
     */

    function get() {
        // Update array of elements
        $is_OK = $this->update();

        if ($is_OK) {
            return array_values($this->array);
        } else {
            return FALSE;
        }
    }

    /*
     * Update Listbox
     * 
     * @return bool Success or NOT
     */

    private function update() {
       return $this->listboxActionCollection->updateListbox($this);
    }

    /*
     * Perform action to Listbox
     * 
     * @param Classes\Listbox\Action\ListboxAction
     * 
     * @return bool Success or NOT
     */

    public function performAction($action) {
        if (!$action instanceof Action\ListboxAction) {
            return FALSE;
        }
        
        // Check if action to be applied to current listbox
        if (!isset($this->array[$action->getTarget()])) {
            return TRUE;
        }

        // Apply action
        return $action->apply($this);
    }

    /*
     * Validate element type
     * 
     * @param mixed $element Element
     * 
     * @return bool Success or NOT
     */

    private function isElementTypeCorrect($element) {
        if (is_string($element) || is_int($element)) {
            return TRUE;
        }

        return FALSE;
    }

}
