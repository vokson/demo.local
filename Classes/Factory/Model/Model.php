<?php

namespace Classes\Factory\Model;

/**
 * Class of Model
 *
 * @author Noskov Alexey
 */
class Model {
    private static $nodes = array(); // Collection of nodes in model
    private static $members = array(); // Collection of rod members in model
    public static $hashTable; // Table of connection btw instances;

    /*
     * Setter for nodes collection
     * 
     * @param \Classes\Instance\Node\Node $object Object to be added to Model
     * @return void
     */
    public static function addInstance($object) {
        
        $uin = $object->getUin();
        
        // NODE
        if ($object instanceof \Classes\Instance\Node\Node) {
            self::$nodes[$uin] = $object;
        }
        // MEMBER
        if ($object instanceof \Classes\Instance\Member\Member) {
            self::$members[$uin] = $object;
        }
    }
    
    /*
     * Get nodes collection
     * 
     * @return \Classes\Instance\Node\Node[] Array of nodes
     */
    public static function &getNodes() {
        return self::$nodes;
    }
    
    /*
     * Get members collection
     * 
     * @return \Classes\Instance\Memeber\Member[] Array of members
     */
    public static function &getMembers() {
        return self::$members;
    }
    
    /*
     * Get Hash Table
     * 
     * @return \Classes\Factory\Model\InstanceHashTable Pointer to Hash Table
     */
    public static function &getHashTable() {
        if (!isset(self::$hashTable)) {
            self::$hashTable = new InstanceHashTable();
        }
        
        return self::$hashTable;
    }
    
    /*
     * Print
     */
    public static function servicePrint() {
        echo "+++ NODES +++<br/>";
        foreach (self::$nodes as $node) {
            $node->servicePrint();
        }
        echo "<br/>";
        
        echo "+++ MEMBERS +++<br/>";
                foreach (self::$members as $member) {
            $member->servicePrint();
        }
        echo "<br/>";
        
        echo "+++ HASH TABLE +++<br/>";
        self::getHashTable()->servicePrint();
        echo "<br/>";
    }
}
