<?php

namespace Classes\Factory\Connection\LoadConnection;

/**
 * Description of LoadConnection
 *
 * @author Noskov Alexey
 */
class GlobalCoordinateSystem extends \Classes\Factory\Connection\Connection{
    
    /*
     * Constructor 
     */
     function __construct() {
         // Set default connection
         $this->connection = 0;
    }
    
    public function get() {
        return $this->connection;
    }
    
    /*
     * PRINT
     */
    public function servicePrint() {
        return "GLOBAL COORDINATE SYSTEM";
    }
}
