<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Factory\Connection;

/**
 * Description of PinConnection
 *
 * @author Noskov Alexey
 */
class PinConnection extends Connection{
           
    /*
     * Constructor from STRING with format "010010"
     * If input isn't correct create "000000" connection
     * 
     * @param string $value Input string
     */
     function __construct($value) {
        // Set 000000
        $this->connection = array(0,0,0,0,0,0);
        
        // Check 1 in STRING
        if (is_string($value) && strlen($value) >= 6) {
            for ($i=0; $i<6; $i++){
                if ($value[$i] == "1") {$this->connection[$i] = 1;}
            }
        }
    }
    
    public function get() {
        return $this->connection;
    }
}
