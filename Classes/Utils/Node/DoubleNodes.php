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
    
    private static $coordinates; // Array with coordinates of double nodes
    private static $uins; // Array with uins of double nodes
    private static $hashTables; // Array of Hash Tables, where it's necessary change links
    
    
    /*
     * Find double nodes. Short algorythm.
     */
    private static function find() {
        
        self::$uins = array();
        $precision = round (log10(1/\Classes\Factory\Model\Model::coordinateTolerance));
        
       // Check all nodes
        $nodes = \Classes\Factory\Model\Model::getNodes();
        
        
        foreach ($nodes as $node) {
            $nodeCoordinates =
                    \Classes\Utils\Math\Constant::stringValue($node->getProperty('x')->get(), $precision) . ';' .
                    \Classes\Utils\Math\Constant::stringValue($node->getProperty('y')->get(), $precision) . ';' .
                    \Classes\Utils\Math\Constant::stringValue($node->getProperty('z')->get(), $precision);
            
//            echo "NODE COORDINATES = $nodeCoordinates<br/>";
            self::$uins[$nodeCoordinates][] = $node->getUin();
        }
        
        // Check all nodes found or not
        if (array_sum(array_map("count", self::$uins)) != count($nodes)) {
            throw new \Classes\Exception\Utils\Node\DoubleNodeException
                ('Problem is obtained during searching of double nodes');
        }
    }
    
    /*
     * Find double nodes. Nodes in Model MUST be sorted.
     */
    /*
    private static function find() {
        
        self::$uins = array();
        
        //Get nodes
        $nodes = array_values(\Classes\Factory\Model\Model::getNodes());
        if (count($nodes) < 2) {return;}
        
        // Set 1st node into UINs array
        $k = 0;
        self::$uins[0][] = $nodes[0]->getUin();
        
        // Check double nodes
        for ($i =1; $i < count($nodes); $i++) {
            
            // if nodes are NOT same, change position in array
            $isSame = \Classes\Instance\Node\Node::compare($nodes[$i-1], $nodes[$i],
            \Classes\Factory\Model\Model::coordinateTolerance);
            
            if ($isSame != 0){
                $k++;
            }
            
            self::$uins[$k][] = $nodes[$i]->getUin();
        }
    }
    */
    
    /*
     * Combine ALL double nodes
     * 
     * @param \Classes\Factory\Model\Table\InstanceHashTable[] $hashTables Tables, where changes to be applied
     * 
     */
    
    public function combineAll($hashTables) {
        // GET HASH TABLE
        self::$hashTables = $hashTables;
        
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
        
        //CHANGE HASH TABLE
        foreach (self::$hashTables as $table) {
            $table->replaceUin($uinAttach, $uinKeep);
        }
        
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
            echo "($x, $y, $z) - ". implode(', ', self::$uins[$i]) ."<br/>";
        }
    }
    
}
