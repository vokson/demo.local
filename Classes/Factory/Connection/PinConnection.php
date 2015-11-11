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
     * If input isn't correct create connection without pin
     * 
     * @param string $value Input string
     */
     function __construct($value = NULL) {
         // Set default connection
         $this->connection = 0;
         
        // Check 1 in STRING
        if (is_string($value) && strlen($value) >= 6) {
            for ($i=0; $i<6; $i++){
                if ($value[$i] == "1") {$this->connection += pow(2,$i);}
            }
        }
    }
    
    public function get() {
        return $this->connection;
    }
}
