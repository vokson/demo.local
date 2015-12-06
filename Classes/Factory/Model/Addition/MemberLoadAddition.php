<?php

namespace Classes\Factory\Model\Addition;

/**
 * Class for addition Member Load to Model (sweepp line method)
 *
 * @author Noskov Alexey
 */
class MemberLoadAddition {

    private static $loadCase; // Load Case
    private static $name; // Name
    private static $direction; // Direction
    private static $x1, $y1, $z1; // Coordinates
    private static $x2, $y2, $z2; // Coordinates
    private static $value1, $value2; // Values
    private static $nodes, $members; // Model's arrays
    private static $hashTable, $loadTable; // Model's table
    
    private static $notFoundObjects; // Object or it's part, which can't be found
    private static $commonMemberLoadTable; // Table for btw nodes and commonMemberLoadArray
    private static $commonMemberLoadArray; // Array of commonMemberLoads
    private static $emptyConnection; // Empty connection for commonMemberLoad

    /*
     * Add Object to Model
     * 
     * @param \Classes\Instance\Load\Member\CommonMemberLoad[] $objects
     * 
     * @return \Classes\Instance\Load\Member\CommonMemberLoad[] Array of NOT found objects
     */

    public static function add($objects) {

        //Prepare arrays
        self::$commonMemberLoadArray = array();
        self::$notFoundObjects = array();

        //Get TABLES
        self::$hashTable = \Classes\Factory\Model\Model::getHashTable();
        self::$loadTable = \Classes\Factory\Model\Model::getLoadTable();
        
        self::$commonMemberLoadTable = new \Classes\Factory\Model\Table\LoadTable();
        self::$emptyConnection = new \Classes\Factory\Connection\LoadConnection\GlobalCoordinateSystem;
        
         

        // Distribute objects to arrays
        foreach ($objects as $object) {
            self::prepareObject($object);
        }

        // DELETE DOUBLE NODES
//        self::$commonMemberLoadTable->servicePrint();
        $tableArray = array(self::$hashTable, self::$commonMemberLoadTable);
        \Classes\Utils\Node\DoubleNodes::combineAll($tableArray);
//        echo "<br/>";
//        self::$commonMemberLoadTable->servicePrint();
        
        // SORT NODES
        \Classes\Factory\Model\Model::sortNodes();
        
        // Get NODES, MEMBERS
        self::$nodes = \Classes\Factory\Model\Model::getNodes();
        self::$members = \Classes\Factory\Model\Model::getMembers();
        
        // Find destination for all Common Member Loads
        self::distribute();
        
        //Return all not found loads
        $notFoundObjects = array();
        foreach (self::$notFoundObjects as $uin) {
            $notFoundObjects[] = self::$commonMemberLoadArray[$uin];
        }
        
        return $notFoundObjects;
    }

    /*
     * Add Object. Get nodes, commonMemberLoad to corresponding arrays.
     * Set connection between them to commonMemberLoadTable
     * 
     * @param \Classes\Instance\Load\Member\CommonMemberLoad $object
     */

    public static function prepareObject($object) {
        // Check type of load
        $type = $object->getProperty('type')->get();

        // Load type isn't correct
        if ($type != "C" && $type != "D") {
            throw new \Classes\Exception\Factory\Model\Addition\MemberLoadAddition\IncorrectTypeException('Incorrect Load Type');
        }

        if ($type == "C" || $type == "D") {
            // NODE 1
            $node1 = new \Classes\Instance\Node\Node();
            $node1->setProperty('x', $object->getProperty('x1'));
            $node1->setProperty('y', $object->getProperty('y1'));
            $node1->setProperty('z', $object->getProperty('z1'));

            // Add nodes to Model
            \Classes\Factory\Model\Model::addInstance($node1);
            self::$commonMemberLoadTable->setConnection($object->getUin(), $node1->getUin(), self::$emptyConnection);
        }

        if ($type == "D") {
            // NODE 2
            $node2 = new \Classes\Instance\Node\Node();
            $node2->setProperty('x', $object->getProperty('x2'));
            $node2->setProperty('y', $object->getProperty('y2'));
            $node2->setProperty('z', $object->getProperty('z2'));

            // Add nodes to Model
            \Classes\Factory\Model\Model::addInstance($node2);
            self::$commonMemberLoadTable->setConnection($object->getUin(), $node2->getUin(), self::$emptyConnection);
        }

        self::$commonMemberLoadArray[$object->getUin()] = $object;
    }

    /*
     * Find connection of Node in Table. Change Array by founded connections.
     * 
     * @param mixed[] $uinArray Array of actual elements
     * @param \Classes\Instance\Node\Node $node Node
     * @param \Classes\Factory\Model\Table\TwoDimensionalTable $table Hash table of connections
     * @param string[] $notFoundUinArray Array of remaining element, which have not been found
     */

    private static function changeActualArray(&$uinArray, $node, $table, &$notFoundUinArray = NULL) {

        $nodeUin = $node->getUin();
        $connections = $table->getConnection($nodeUin);

        //Change Array
        if (count($connections) > 0) {

            foreach ($connections as $uin => $c) {
                // Here $uin is uin of member connected with node

                if (isset($uinArray[$uin])) {
                    // If element is already included in Array -> delete it
                    unset($uinArray[$uin]);
                    // Add not found element to array, if necessary
                    if (!is_null($notFoundUinArray)) {
                        $notFoundUinArray[] = $uin;
                    }
                } else {
                    // If member is NOT included in Array -> add it
                    $keys = array_keys($table->getConnection($uin));
                    $endNumber = array_search($nodeUin, $keys);
                    $uinArray[$uin] = $endNumber;
                }
            }
        }
    }

    /*
     * Prepare properties of CommonMemberLoad
     * 
     * @param \Classes\Instance\Load\Member\CommonMemberLoad $load Load
     */
    private static function getLoadProperties($load) {
        //Get Properties
        self::$loadCase = $load->getProperty('loadCase');
        self::$name = $load->getProperty('name');

        $isDirectionCorrect = \Classes\Utils\Load\LoadDirection::get(self::$direction,
                $load->getProperty('direction')->get());

        if (!$isDirectionCorrect) {
            throw new \Classes\Exception\Factory\Model\Addition\MemberLoadAddition\IncorrectDirectionException('Incorrect Load Direction');
        }

        // Make direction Property
        self::$direction = new \Classes\Value\IntValue(self::$direction);
        
        self::$x1 = $load->getProperty('x1');
        self::$y1 = $load->getProperty('y1');
        self::$z1 = $load->getProperty('z1');
        self::$value1 = $load->getProperty('value1');
        
        self::$x2 = $load->getProperty('x2');
        self::$y2 = $load->getProperty('y2');
        self::$z2 = $load->getProperty('z2');
        self::$value2 = $load->getProperty('value2');
    }
    
    /*
     * Set general properties to Load and add it to Model
     * 
     * @param \Classes\Instance\Load\Load $load Load
     */
    private static function setLoadProperties($load) {
        // Set Properties
        $load->setProperty('loadCase', self::$loadCase);
        $load->setProperty('name', self::$name);
        $load->setProperty('direction', self::$direction);

        // Add to model
        \Classes\Factory\Model\Model::addInstance($load); 
    }
    
    /*
     * Find place for application of commonMemberLoad
     * 
     */
    private static function distribute() {
       
        // Array [uin] => [0 - 1st point, 1 - 2nd point]
        $actualMemberUins = array();
        $actualCommonMemberLoadUins = array();

        foreach (self::$nodes as $node) {
            $node->servicePrint();
            
            //Change arrays of actual elements
            self::changeActualArray($actualMemberUins, $node, self::$hashTable);
            self::changeActualArray($actualCommonMemberLoadUins, $node, self::$commonMemberLoadTable, self::$notFoundObjects);
            
            //Add CommonMemberLoads
            foreach ($actualCommonMemberLoadUins as $loadUin=>$endNumber) {
               // Get CommonMemberLoad
                $commonMemberLoad = self::$commonMemberLoadArray[$loadUin];
             
                // Get properties
                self::getLoadProperties($commonMemberLoad);
                
               // Check type of load
               $type = $commonMemberLoad->getProperty('type')->get();
               
               if ($type == "C") {
                   $isFound = self::addConcentratedCommonMemberLoad($node, $actualMemberUins);
                   // if commonMemberLoad has not been found, add Uin to notFoundArray
                   if (!$isFound) {
                       self::$notFoundObjects[] = $loadUin;
                   }
                   // delete checked CommonMemberLoad from actual array
                   unset($actualCommonMemberLoadUins[$loadUin]);
               }
               
            }
        }
    }
    
    /*
     * Add concentrated CommonMemberLoad
     * 
     * @param \Classes\Instance\Node\Node $node Node
     * @param mixed[] $actualMemberUins Array of actual members
     */
    private static function addConcentratedCommonMemberLoad($node, $actualMemberUins) {
        
        $isFound = FALSE;
        
        // Check if concentrated load is placed on Node or Member
        if (!is_null(self::$hashTable->getConnection($node->getUIn()))) {
            // This is nodal load
            $isFound = TRUE;
                
            // Make new node load
            $load = new \Classes\Instance\Load\Node\NodeLoad;

            // Set Properties
            $load->setProperty('value', self::$value1);

            // Add connection
            self::$loadTable->setConnection($load->getUin(), $node->getUin(),
                    new \Classes\Factory\Connection\LoadConnection\GlobalCoordinateSystem);

        } else {
            // This is load on member
            // Prepare Load Point
            $loadPoint = new \Classes\Utils\AbstractInstance\Point(
                    self::$x1->get(),
                    self::$y1->get(),
                    self::$z1->get()
            );

            // Try to find member with necessary coordinates
            while ((list($memberUin, $memberEndNumber) = each($actualMemberUins)) && $isFound === FALSE) {

                $connections = array_keys(self::$hashTable->getConnection($memberUin));
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
                if (\Classes\Utils\Math\Points::isPointOnLine($loadPoint, $line) == 3) {

                    $isFound = TRUE;

                    // Make new node load
                    $load = new \Classes\Instance\Load\Member\ConcenratedMemberLoad;

                    $position = new \Classes\Value\FloatValue(
                            \Classes\Utils\Math\Points::twoPointsDistance($node1Point, $loadPoint));

                    // Set Properties
                    $load->setProperty('value', self::$value1);
                    $load->setProperty('position', $position);


                    // Add connection
                    self::$loadTable->setConnection($load->getUin(), $memberUin,
                            new \Classes\Factory\Connection\LoadConnection\GlobalCoordinateSystem);

                } 

            }
        
        }
        
        // If Load has been found, add properties and add to Model
        if ($isFound) {
            self::setLoadProperties($load);
        }
        
        return $isFound;
    }
}
