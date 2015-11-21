<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Factory\Connection;

/**
 * Description of Connection
 *
 * @author Noskov Alexey
 */
abstract class Connection {

    protected $connection;
    
    /*
     * Get connection
     * 
     * @return Classes\Factory\Connection\Connection
     */
    abstract public function get();
    
    /*
     * PRINT
     */
    abstract public function servicePrint();
    
}
