<?php

namespace Classes\Factory\Connection\MassMatrixConnection;

/**
 * Class MassMatrixConenction 
 *
 * @author Noskov Alexey
 */
class MassMatrixConnection extends \Classes\Factory\Connection\Connection{
    
    /*
     * Constructor 
     * 
     * @param double[] $value Associative array of load cases' coefficients
     * For example, array ( 1 => 1.2, 3 => 1.5 )
     */
     function __construct($value) {
         if (is_array($value)) {
             $this->connection = $value;
         }
    }
    
    public function get() {
        return $this->connection;
    }
}
