<?php

namespace Classes\Listbox\Collection;

/**
 * Description of ListboxActionCollection
 *
 * @author Noskov Alexey
 */
class ListboxActionCollection {
    
    private $actionArray = array(); // Array of Listbox Actions
    private $lastActionIndexArray = array(); // Array of indexes of last applied actions
    private $currentActionIndex = -1; //Last index of Action in actionArray
    const maxCountOfActionArray = 30;
    
    /*
     * Add Listbox Action
     * 
     * @param \Classes\Listbox\Action\ListboxAction $action Listbox Action
     * 
     * @return bool Succes or NOT
     */
    function addAction($action) {
       if (! $action instanceof \Classes\Listbox\Action\ListboxAction) {
            return FALSE;
        }
        
        // If any ListBox has been already registered in Collection, add action
        if (count($this->lastActionIndexArray) > 0) {
            $this->currentActionIndex++;
            $this->actionArray[$this->currentActionIndex] = $action;
        }
        
        return TRUE;
    }
    
    
    /*
     * Register Listbox
     * 
     * @param \Classes\Listbox\Listbox $listbox ListBox
     * 
     * @return bool Success or not
     */
    function registerListbox($listbox) {
        if (! $listbox instanceof \Classes\Listbox\Listbox) {
            return FALSE;
        }
        
        if (isset($this->lastActionIndexArray[$listbox->getUin()])) {
            return FALSE;
        }
        
        // Set last index to current action index
        $this->lastActionIndexArray[$listbox->getUin()] = $this->currentActionIndex;
        return TRUE;
    }
    
    /*
     * Update list box
     * 
     * @param \Classes\Listbox\Listbox $listbox
     * 
     * @return bool Success or NOT
     */
    function updateListbox(&$listbox) {
        if (! $listbox instanceof \Classes\Listbox\Listbox) {
            return FALSE;
        }
        
        if (!isset($this->lastActionIndexArray[$listbox->getUin()])) {
            return FALSE;
        }
        
//        echo "+++ updateListbox: UIN = ".$listbox->getUin()."<br/>";
        
        $lastActionIndex = $this->lastActionIndexArray[$listbox->getUin()];
        
        // If there are not applied Actions
        if ($this->currentActionIndex > $lastActionIndex) {
           
            $is_OK = TRUE; $i = $lastActionIndex + 1;
            
            while ($is_OK === TRUE && $i <= $this->currentActionIndex) {
                // DO ACTION
                $is_OK = $listbox->performAction($this->actionArray[$i]);
               
                // if OK, change last applied action indox for the listbox
                if ($is_OK) {
                    $this->lastActionIndexArray[$listbox->getUin()] = $i;
                }
                
                $i++;
            }
            // CLEAN
            $this->cleanUsedActions();
//            echo "IS_OK ".print_r($is_OK)."<br/>";
            return $is_OK;
        }
        
        
        
        return TRUE;
    }
    
    /*
     * Clean array of actions
     * 
     */
    private function cleanUsedActions() {
        if (count($this->actionArray) > self::maxCountOfActionArray) {

            $startIndex = array_keys($this->actionArray)[0];
            $stopIndex = min($this->lastActionIndexArray);
//            echo "START = $startIndex; STOP = $stopIndex<br/>";
            
            for($i = $startIndex; $i <= $stopIndex; $i++) {
//                echo "I = $i<br/>";
                unset($this->actionArray[$i]);
            }
        }
    }
    
    /*
     * PRINT
     */
    public function servicePrint() {

        echo "CURRENT INDEX = ".  $this->currentActionIndex."<br/>";
        echo "LAST APPLIED INDEXES:<br/>";
        
        foreach($this->lastActionIndexArray as $key => $value) {
            echo "   [$key] => $value<br/>";
        }
        
        echo "ACTIONS:<br/>";
        foreach ($this->actionArray as $key => $value) {
            echo "   [$key] => ";
            $value->servicePrint();
            echo "<br/>";
        }
        
    }
}
