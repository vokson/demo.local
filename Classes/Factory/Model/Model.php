<?php

namespace Classes\Factory\Model;

/**
 * Class of Model
 *
 * @author Noskov Alexey
 */
class Model {
    
    //Tolerance in meters. Use 10^x (x < -1) ONLY. Like 0.1, 0.01, 0.001..
    public static $coordinateTolerance; 
    
    private static $nodes = array(); // Collection of nodes in model
    private static $members = array(); // Collection of rod members in model
    private static $loads = array(); // Collection of loads in model
    private static $loadCases = array(); // Collection of load cases
    private static $steelMemberCheckGroups = array(); // Collection of group for steel member check
    private static $hashTable; // Table of connection btw instances;
    private static $restraintTable; // Table of restraints;
    private static $loadTable; // Table of loads;
    private static $memberActionCollection; //Collection of listboxes
    private static $massMatrixTable; // Table of coefficients for mass matrix

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
         // LOAD
        if ($object instanceof \Classes\Instance\Load\Load) {
            self::$loads[$uin] = $object;
        }
        // LOAD CASE
        if ($object instanceof \Classes\Instance\LoadCase\LoadCase) {
            self::$loadCases[$uin] = $object;
        }
        // STEEL MEMBER CHECK GROUP
        if ($object instanceof \Classes\Instance\Group\SteelMember\SteelMemberCheckGroup) {
            self::$steelMemberCheckGroups[$uin] = $object;
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
        // LOAD
        if (isset(self::$loads[$uin])) {
            unset(self::$loads[$uin]);
            return TRUE;
        }
        
        // LOAD CASE
        if (isset(self::$loadCases[$uin])) {
            unset(self::$loadCases[$uin]);
            return TRUE;
        }
        
        // STEEL MEMBER CHECK GROUP
        if (isset(self::$steelMemberCheckGroups[$uin])) {
            unset(self::$steelMemberCheckGroups[$uin]);
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
     * Sort nodes
     * 
     * @return bool Success or NOT
     */
    static public function sortNodes() {
        $values = array_values(self::$nodes);

        $isSorted = usort($values, function ($a, $b) {
            return \Classes\Instance\Node\Node::compare($a, $b,
                    self::$coordinateTolerance);
        });
        
        if ($isSorted) {
            self::$nodes = array();
            foreach($values as $node) {
                self::$nodes[$node->getUin()] = $node;
            }
        }
        
        return $isSorted;
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
     * Get load cases collection
     * 
     * @return \Classes\Instance\LoadCase\LoadCase[] Array of load cases
     */
    public static function &getLoadCases() {
        return self::$loadCases;
    }
    
    /*
     * Get loads collection
     * 
     * @return \Classes\Instance\Loade\Load[] Array of loads
     */
    public static function &getLoads() {
        return self::$loads;
    }
    
    /*
     * Get steel member check groups
     * 
     * @return \Classes\Instance\Group\SteelMember\SteelMemberCheckGroup[] Array of groups
     */
    public static function &getSteelMemberCheckGroups() {
        return self::$steelMemberCheckGroups;
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
     * Get Load Table
     * 
     * @return \Classes\Factory\Model\Table\LoadTable Pointer to Load Table
     */
    public static function &getLoadTable() {
        if (!isset(self::$loadTable)) {
            self::$loadTable = new Table\LoadTable();
        }
        
        return self::$loadTable;
    }
    
    /*
     * Get Load Table
     * 
     * @return \Classes\Factory\Model\Table\MassMatrixTable Pointer to Table
     */
    public static function &getMassMatrixTable() {
        if (!isset(self::$massMatrixTable)) {
            self::$massMatrixTable = new Table\MassMatrixTable();
        }
        
        return self::$massMatrixTable;
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
        foreach (self::$nodes as $object) {
            $object->servicePrint();
        }
        echo "<br/>";
        
        echo "+++ MEMBERS +++<br/>";
        foreach (self::$members as $object) {
            $object->servicePrint();
        }
        echo "<br/>";
        
        echo "+++ LOAD CASES +++<br/>";
        foreach (self::$loadCases as $object) {
            $object->servicePrint();
        }
        echo "<br/>";
        
        echo "+++ LOADS +++<br/>";
        foreach (self::$loads as $object) {
            $object->servicePrint();
        }
        echo "<br/>";
        
        echo "+++ STEEL MEMBER CHECK GROUPS +++<br/>";
        foreach (self::$steelMemberCheckGroups as $object) {
            $object->servicePrint();
        }
        echo "<br/>";
        
        echo "+++ HASH TABLE +++<br/>";
        self::getHashTable()->servicePrint();
        echo "<br/>";
        
        echo "+++ LOAD TABLE +++<br/>";
        self::getLoadTable()->servicePrint();
        echo "<br/>";
        
         echo "+++ MASS MATRIX TABLE +++<br/>";
        self::getMassMatrixTable()->servicePrint();
        echo "<br/>";
        
        echo "+++ RESTRAINT TABLE +++<br/>";
        self::getRestraintTable()->servicePrint();
        echo "<br/>";
        
        echo "+++ MEMBER ACTION COLLECTION +++<br/>";
        self::getMemberActionCollection()->servicePrint();
        echo "<br/>";
    }
}
