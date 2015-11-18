<?php

namespace Classes\Factory\Model\Addition;

/**
 * Class for addition Member Load to Model
 *
 * @author Noskov Alexey
 */
class MemberLoadAddition {
    private static $object; // Object to be applied
    private static $load; // New load
    private static $loadCase; // Load Case
    private static $name; // Name
    private static $direction; // Direction
    private static $x1,$y1,$z1,$x2,$y2,$z2; // Coordinates
    private static $value1, $value2; // Values
    
    private static $nodes, $members; // Model's arrays
    private static $hashTable, $loadTable; // Model's table
    
    private static $isFound; // Boolean
    private static $notFoundObject; // Object or it's part, which can't be found


    /*
     * Add Object to Model
     * 
     * @param \Classes\Instance\Load\Member\CommonMemberLoad $object
     * @param \Classes\Instance\Load\Member\CommonMemberLoad $notFoundObject
     * 
     * @return bool Found or NOT
     */
    public static function add($object, &$notFoundObjects) {
        // get OBJECT
        self::$object = $object;
        // Get NODES, MEMBERS
        self::$nodes = \Classes\Factory\Model\Model::getNodes();
        self::$members = \Classes\Factory\Model\Model::getMembers();
        //Get TABLES
        self::$hashTable = \Classes\Factory\Model\Model::getHashTable();
        self::$loadTable = \Classes\Factory\Model\Model::getLoadTable();
        
        // Set FOUND to FALSE
        self::$isFound = FALSE;
        
        //Get Properties
        self::$loadCase = $object->getProperty('loadCase');
        self::$name = $object->getProperty('name');
        
        $isDirectionCorrect = \Classes\Utils\Load\LoadDirection::get(self::$direction,
                $object->getProperty('direction')->get());
        
        if (!$isDirectionCorrect) {
            throw new \Classes\Exception\Factory\Model\Addition\MemberLoadAddition\IncorrectDirectionException('Incorrect Load Direction');
        }
        
        // Make direction Property
        self::$direction = new \Classes\Value\IntValue(self::$direction);
        
        self::$x1 = $object->getProperty('x1');
        self::$y1 = $object->getProperty('y1');
        self::$z1 = $object->getProperty('z1');
        self::$value1 = $object->getProperty('value1');
        
        // Check type of load
        $type = $object->getProperty('type')->get();
        
         // Load type isn't correct
        if ($type != "C" && $type != "D") {
            throw new \Classes\Exception\Factory\Model\Addition\MemberLoadAddition\IncorrectTypeException('Incorrect Load Type');
        }
        
        if ($type == "C") {
            // If $object will not be found
            // $object itself will be returned
            self::$notFoundObject = self::$object;
        
            // TRY TO APPLY TO NODE
            self::addNodeConcentratedLoad ();
            
            // TRY TO APPLY TO MEMBER
            if (!self::$isFound) {
                self::addMemberConcentratedLoad ();
            }
        }
        
        if ($type == "D") {
            self::addDistributedLoad ();
        }
        
        // If $object is NOT applied
        if (!self::$isFound) {
            $notFoundObjects = self::$notFoundObject;
            return FALSE;
        }
            
        // Set Properties
        self::$load->setProperty('loadCase', self::$loadCase);
        self::$load->setProperty('name', self::$name);
        self::$load->setProperty('direction', self::$direction);
        
        // Add to model
        \Classes\Factory\Model\Model::addInstance(self::$load);
        
        return TRUE;
    }
    
    /*
     * Add Concentrated Load to Model
     */
    private static function addNodeConcentratedLoad() {
        
        // Prepare Load Point
        $loadPoint = new \Classes\Utils\AbstractInstance\Point(
                self::$x1->get(),
                self::$y1->get(),
                self::$z1->get()
        );
        
        // Try to find node with necessary coordinates
        $i=0;
        $nodes = array_values(self::$nodes);
        while ($i < count($nodes) && self::$isFound === FALSE) {
            
            $node = $nodes[$i];
            
            // Prepare Node Point
            $nodePoint = new \Classes\Utils\AbstractInstance\Point(
                $node->getProperty('x')->get(),
                $node->getProperty('y')->get(),
                $node->getProperty('z')->get()
            );
            
            // Compare coordinates
            if (\Classes\Utils\Math\Points::isPointSame($loadPoint, $nodePoint)) {
                
                self::$isFound = TRUE;
                
                // Make new node load
                self::$load = new \Classes\Instance\Load\Node\NodeLoad;
                
                // Set Properties
                self::$load->setProperty('value', self::$value1);
                
                // Add connection
                self::$loadTable->setConnection(self::$load->getUin(), $node->getUin(),
                        new \Classes\Factory\Connection\LoadConnection\GlobalCoordinateSystem);
            }
            
            $i++;
        }
    }
    
    /*
     * Add Concentrated Load to Model
     */
    private static function addMemberConcentratedLoad() {
        
        // Prepare Load Point
        $loadPoint = new \Classes\Utils\AbstractInstance\Point(
                self::$x1->get(),
                self::$y1->get(),
                self::$z1->get()
        );
        
        // Try to find member with necessary coordinates
        $i=0;
        $members = array_values(self::$members);
        while ($i < count($members) && self::$isFound === FALSE) {
            
            $member = $members[$i];
            $connections = array_keys(self::$hashTable->getConnection($member->getUin()));
            $node1 = self::$nodes[$connections[0]];
            $node2 = self::$nodes[$connections[1]];
            
            // Prepare Node Point
            $node1Point = new \Classes\Utils\AbstractInstance\Point(
                $node1->getProperty('x')->get(),
                $node1->getProperty('y')->get(),
                $node1->getProperty('z')->get()
            );
            
             $node2Point = new \Classes\Utils\AbstractInstance\Point(
                $node2->getProperty('x')->get(),
                $node2->getProperty('y')->get(),
                $node2->getProperty('z')->get()
            );
             
            // Prepare Line
             $line = new \Classes\Utils\AbstractInstance\Line($node1Point, $node2Point);
            
            // Compare coordinates
            if (\Classes\Utils\Math\Points::isPointOnLine($loadPoint, $line)) {
                
                self::$isFound = TRUE;
                
                // Make new node load
                self::$load = new \Classes\Instance\Load\Member\ConcenratedMemberLoad;
                
                $position = new \Classes\Value\FloatValue(
                        \Classes\Utils\Math\Points::twoPointsDistance($node1Point, $loadPoint));
                
                // Set Properties
                self::$load->setProperty('value', self::$value1);
                self::$load->setProperty('position', $position);
                
                
                // Add connection
                self::$loadTable->setConnection(self::$load->getUin(), $member->getUin(),
                        new \Classes\Factory\Connection\LoadConnection\GlobalCoordinateSystem);
            }
            
            $i++;
        }
        
    }
    
    /*
     * Add Distributed Load to Model
     */
    private static function addDistributedLoad() {
        self::$x2 = self::$object->getProperty('x2');
        self::$y2 = self::$object->getProperty('y2');
        self::$z2 = self::$object->getProperty('z2');
        self::$value2 = self::$object->getProperty('value2');
    }
}
