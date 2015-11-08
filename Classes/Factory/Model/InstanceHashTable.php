<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Factory\Model;

/**
 * Class to store depencies between instancies
 *
 * @author Noskov Alexey
 */
class InstanceHashTable {
    private $table = array(); // array to store hash table
    
    /*
     * Add connection to Hash Table
     * 
     * @param string $uin1 Instance's UIN
     * @param string $uin2 Instance's UIN
     * @param \Classes\Factory\Connection\Connection $connection Connection
     * 
     */
    public function setConnection($uin1, $uin2, $connection) {
        if (is_string($uin1) &&
            is_string($uin2) &&
            ($connection instanceof \Classes\Factory\Connection\Connection)) {
            
            $this->table[$uin1][$uin2] = $connection;
            $this->table[$uin2][$uin1] = $connection;
        }
    }
    
    /*
     * Remove connection from Hash Table
     * 
     * @param string $uin1 Instance's UIN
     * @param string $uin2 Instance's UIN
     */
    public function removeConnection($uin1, $uin2) {
        if (is_string($uin1) &&
            is_string($uin2)) {
            
            if (isset($this->table[$uin1][$uin2])) {
                unset($this->table[$uin1][$uin2]);
            }
            
            if (isset($this->table[$uin2][$uin1])) {
                unset($this->table[$uin2][$uin1]);
            }
        }
    }
    
    /*
     * Get connection from Hash Table
     * 
     * @param string $uinSource Connection will be added FROM this UIN
     * @param string $uinTarget Connection will be added TO this UIN
     * 
     * @return mixed[] Connection from Hash Table
     */
    public function getConnection($uinSource, $uinTarget = NULL) {
        if (is_string($uinSource)) {
            if (isset($this->table[$uinSource])) {

                if (is_string($uinTarget)) {
                    if (isset($this->table[$uinSource][$uinTarget])) {
                        return $this->table[$uinSource][$uinTarget];
                    }
                }

                if ($uinTarget === NULL) {
                    return $this->table[$uinSource];
                }
            }
        }
    }
    
    /*
     * Print Hash Table
     */
    public function servicePrint() {
        foreach ($this->table as $sourceUin => $array) {
            foreach ($array as $targetUin => $connection) {
                echo $sourceUin . " > " . $targetUin . " = " . $connection->get() . "<br/>";
            }
        }
    }
}
