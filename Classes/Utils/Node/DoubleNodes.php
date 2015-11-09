<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Utils\Node;

/**
 * Class to operate double nodes with same coordinates
 *
 * @author Noskov Alexey
 */
class DoubleNodes {
    
    private static $coordinates = array(); // Array with coordinates of double nodes
    private static $uins = array(); // Array with uins of double nodes
    
    /*
     * Find double nodes
     */
    private static function find() {
        
       // Check all nodes
        $nodes = \Classes\Factory\Model\Model::getNodes();
        
        foreach ($nodes as $node) {
            
            // Get $node coordinates
            $xNode = $node->getProperty('x')->get();
            $yNode = $node->getProperty('y')->get();
            $zNode = $node->getProperty('z')->get();
            
            $isFound = false;
            $i = 0;
            
            while ($i < count(self::$coordinates) && $isFound === FALSE) {
                
                // Get coordinates of checked node
                $xExist = self::$coordinates[$i][0];
                $yExist = self::$coordinates[$i][1];
                $zExist = self::$coordinates[$i][2];
                
                // Check same point or not
                $isPointSame = \Classes\Utils\Math\Points::isPointSame($xNode, $yNode, $zNode, $xExist, $yExist, $zExist);
                
                // SAME
                if ($isPointSame) {
                    self::$uins[$i][] = $node->getUin();
                    $isFound = TRUE;
                } 
                
                $i++;
            }
            
            // NOT FOUND
            if (!$isFound) {
                // ADD to existing nodes
                self::$coordinates[] = array($xNode, $yNode, $zNode);
                self::$uins[] = array($node->getUin());
            }
        }
        
        // Check all nodes found or not
        if (array_sum(array_map("count", self::$uins)) != count($nodes)) {
            throw new \Classes\Exception\Utils\Node\DoubleNodeException
                ('Problem is obtained during searching of double nodes');
        }
    }
    
    /*
     * Get double nodes
     * 
     * @return string[][]
     */
    public static function get() {
        // FIND
        self::find();
        
        //GET
        return self::$uins;
    }
    
    /*
     * Combine ALL double nodes
     */
    
    public function combineAll() {
        // FIND
        self::find();
        
        //COMBINE
        foreach (self::$uins as $uinArray) {
            if (count($uinArray) > 1) {
                for ($i=1; $i<count($uinArray); $i++) {
                    self::combineTwoNodes($uinArray[0], $uinArray[$i]);
                }
            }
        }
    }
    
    /*
     * Combine uinKeep and uinAttach nodes.
     * 
     * @param string $uinKeep Node to be kept
     * @param string $uinAttach Node to be deleted
     */
    
    private static function combineTwoNodes($uinKeep, $uinAttach) {
        
        //DELETE NODE
        $nodes = \Classes\Factory\Model\Model::getNodes();
        unset($nodes[$uinAttach]);
        
        //CHANGE HASSH TABLE
        $hashTable = \Classes\Factory\Model\Model::getHashTable();
        $hashTable->replaceUin($uinAttach, $uinKeep);
    }
    
}
