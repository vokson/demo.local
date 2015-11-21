<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Factory\Model\Table;

/**
 * Class to store depencies for instancies
 *
 * @author Noskov Alexey
 */
class OneDimensionalTable {

    protected $table = array(); // array to store table

    /*
     * Get Table
     * 
     * @return Classes\Factory\Connection\Connection[]
     */
    public function getTable() {
        return $this->table;
    }
    
    /*
     * Add connection to Table
     * 
     * @param string $uin Instance's UIN
     * @param \Classes\Factory\Connection\Connection $connection Connection
     * 
     */

    public function setConnection($uin, $connection) {
        if (is_string($uin) &&
                ($connection instanceof \Classes\Factory\Connection\Connection)) {

            $this->table[$uin] = $connection;
        }
    }

    /*
     * Remove connection from Table
     * 
     * @param string $uin Instance's UIN
     */

    public function removeConnection($uin) {
        if (is_string($uin)) {

            if (isset($this->table[$uin])) {
                unset($this->table[$uin]);
            }
        }
    }

    /*
     * Get connection from Table
     * 
     * @param string $uin Instance's UIN
     * 
     * @return \Classes\Factory\Connection\Connection $connection Connection
     */

    public function getConnection($uin) {
        if (is_string($uin)) {
            if (isset($this->table[$uin])) {
                return $this->table[$uin];
            }
        }
    }

    /*
     * Replace UIN in TABLE
     * 
     * @param string $from Instance's UIN
     * @param string $to Instance's UIN
     */

    public function replaceUin($from, $to) {
        // Get connection related to $from
        $connection = $this->getConnection($from);
        
        // If there is NOT connection
        if (empty($connection)) {
            return;
        }

        // Modify $to
        $this->table[$to] = $connection;

        // Delete $from
        unset($this->table[$from]);
    }

    /*
     * Print Table
     */
    public function servicePrint() {
        foreach ($this->table as $uin => $connection) {
            echo $uin . " = " . $connection->servicePrint() . "<br/>";
        }
    }

}
