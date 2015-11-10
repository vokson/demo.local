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
            $pointNode = new \Classes\Utils\AbstractInstance\Point(
                    $node->getProperty('x')->get(),
                    $node->getProperty('y')->get(),
                    $node->getProperty('z')->get()
                    );
            
            $isFound = false;
            $i = 0;
            
            while ($i < count(self::$coordinates) && $isFound === FALSE) {
                
                // Get coordinates of checked node
                $pointExist = new \Classes\Utils\AbstractInstance\Point(
                    self::$coordinates[$i][0],
                    self::$coordinates[$i][1],
                    self::$coordinates[$i][2]
                    );
                
                // Check same point or not
                $isPointSame = \Classes\Utils\Math\Points::isPointSame($pointNode, $pointExist);
                
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
                self::$coordinates[] = array($pointNode->x, $pointNode->y, $pointNode->z);
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
//        self::servicePrint();
        
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
//        echo "COMBINE TWO NODES KEEP = $uinKeep, ATTACH = $uinAttach<br/>";
        
        //DELETE NODE
        \Classes\Factory\Model\Model::deleteInstance($uinAttach);
        
        //CHANGE HASSH TABLE
        $hashTable = \Classes\Factory\Model\Model::getHashTable();
        $hashTable->replaceUin($uinAttach, $uinKeep);
        
//        \Classes\Factory\Model\Model::servicePrint();
    }
    
    /*
     * PRINT
     */
    public static function servicePrint() {
        
       echo "+++ DOUBLE NODES +++<br/>";
        
        for ($i=0; $i < count(self::$coordinates); $i++) {
            $x = self::$coordinates[$i][0];
            $y = self::$coordinates[$i][1];
            $z = self::$coordinates[$i][2];
            echo "($x, $y, $z) - ". implode(',', self::$uins[$i]) ."<br/>";
        }
    }
    
}
