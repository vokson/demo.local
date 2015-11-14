<?php

namespace Classes\Factory\Model;

/**
 * Class of Model
 *
 * @author Noskov Alexey
 */
class Model {
    
    const coordinateTolerance = 0.01; //Tolerance in meters
    
    private static $nodes = array(); // Collection of nodes in model
    private static $members = array(); // Collection of rod members in model
    private static $hashTable; // Table of connection btw instances;
    private static $restraintTable; // Table of restraints;
    private static $memberActionCollection; //Collection of listboxes

    /*
     * Add Instance
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
     * Delete Instance
     * 
     * @param string $uin
     * 
     * @return bool
     */
    public static function deleteInstance($uin) {
        
        // NODE
        if (isset(self::$nodes[$uin])) {
            unset(self::$nodes[$uin]);
            return TRUE;
        }
        // MEMBER
        if (isset(self::$members[$uin])) {
            unset(self::$members[$uin]);
            
            // ADD ACTION TO COLLECTION
            $this->getMemberActionCollection()->addAction(
                    new \Classes\Listbox\Action\DeleteListboxAction($uin));
            
            return TRUE;
        }
        
        return FALSE;
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
     * @return \Classes\Factory\Model\Table\InstanceHashTable Pointer to Hash Table
     */
    public static function &getHashTable() {
        if (!isset(self::$hashTable)) {
            self::$hashTable = new Table\InstanceHashTable();
        }
        
        return self::$hashTable;
    }
    
    /*
     * Get Restraint Table
     * 
     * @return \Classes\Factory\Model\Table\RestraintTable Pointer to Restraint Table
     */
    public static function &getRestraintTable() {
        if (!isset(self::$restraintTable)) {
            self::$restraintTable = new Table\RestraintTable();
        }
        
        return self::$restraintTable;
    }
    
    /*
     * Get Listbox Collection
     * 
     * @return \Classes\Listbox\Collection\ListboxActionCollection Pointer to colelciton
     */
    public static function &getMemberActionCollection() {
        if (!isset(self::$memberActionCollection)) {
            self::$memberActionCollection = new \Classes\Listbox\Collection\ListboxActionCollection();
        }
        
        return self::$memberActionCollection;
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
        
        echo "+++ RESTRAINT TABLE +++<br/>";
        self::getRestraintTable()->servicePrint();
        echo "<br/>";
        
        echo "+++ MEMBER ACTION COLLECTION +++<br/>";
        self::getMemberActionCollection()->servicePrint();
        echo "<br/>";
    }
}
