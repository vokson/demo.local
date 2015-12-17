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
        $tableArray = array(self::$hashTable, self::$commonMemberLoadTable);
        \Classes\Utils\Node\DoubleNodes::combineAll($tableArray);
        
        // SORT NODES
        \Classes\Factory\Model\Model::sortNodes();
        
        // Get NODES, MEMBERS
        self::$nodes = \Classes\Factory\Model\Model::getNodes();
        self::$members = \Classes\Factory\Model\Model::getMembers();
        
        // Find destination for all Common Member Loads
        self::distribute();
        
        // Return all not found loads
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
//            $node->servicePrint();
            
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
               switch ($commonMemberLoad->getProperty('type')->get()) {
                   case "C":
                       $isFound = self::addConcentratedCommonMemberLoad($node, $commonMemberLoad, $actualMemberUins);
                       
                       // delete checked CommonMemberLoad from actual array
                       unset($actualCommonMemberLoadUins[$loadUin]);
                       
                       break;
                   
                   case "D":
                       $isFound = self::addDistributedCommonMemberLoad($node, $commonMemberLoad, $endNumber, $actualMemberUins);
                       
                       // delete checked CommonMemberLoad from actual array
                       if ($isFound) {
                           unset($actualCommonMemberLoadUins[$loadUin]);
                       }
                       
                       break;
                       
                   default:
                       break;
               }
               
               
               
            }
        }
    }
    
    /*
     * Add distributed CommonMemberLoad
     * 
     * @param \Classes\Instance\Node\Node $node Node
     * @param \Classes\Instance\Load\Member\CommonMemberLoad $commonMemberLoad Common Member Load
     * @param int $commonMemberLoadEndNumber End number of Common Member Load [0 - 1st node, 1 - 2nd node]
     * @param mixed[] $actualMemberUins Array of actual members
     */
    private static function addDistributedCommonMemberLoad($node, $commonMemberLoad, $commonMemberLoadEndNumber, $actualMemberUins) {
        
        $isFound = FALSE;
        
        // Get Nodes of Common Member Load
        $loadConnections = array_keys(self::$commonMemberLoadTable->getConnection($commonMemberLoad->getUin()));
        $loadNode1 = self::$nodes[$loadConnections[0]];
        $loadNode2 = self::$nodes[$loadConnections[1]];
        
        // Prepare Load Point
        $loadPoint1 = \Classes\Utils\AbstractInstance\Point::createFromNode($loadNode1);
        $loadPoint2 = \Classes\Utils\AbstractInstance\Point::createFromNode($loadNode2);
        
        // Prepare Load Line
        $loadLine = new \Classes\Utils\AbstractInstance\Line($loadPoint1, $loadPoint2);
        $loadLineLength = $loadLine->length();
        
        // Try to find member with necessary coordinates
         while ((list($memberUin, $memberEndNumber) = each($actualMemberUins)) && $isFound === FALSE) {
           
            $memberConnections = array_keys(self::$hashTable->getConnection($memberUin));
            $memberNode1 = self::$nodes[$memberConnections[0]];
            $memberNode2 = self::$nodes[$memberConnections[1]];
            
            // Prepare Node Point
            $memberPoint1 = \Classes\Utils\AbstractInstance\Point::createFromNode($memberNode1);
            $memberPoint2 = \Classes\Utils\AbstractInstance\Point::createFromNode($memberNode2);
             
            // Prepare Line
            $memberLine = new \Classes\Utils\AbstractInstance\Line($memberPoint1, $memberPoint2);
            
            // Array for arrays with overlapping coordinates
             $loadOverlap = array(); $memberOverlap = array();
             
             // Find overlapping of lines
            if (\Classes\Utils\Math\Lines::findOverlappingOfTwoLines($loadLine, $memberLine, $loadOverlap, $memberOverlap)) {
                
                $isFound = TRUE;
                
                // Make new node load
                $load = new \Classes\Instance\Load\Member\DistributedMemberLoad();
                
                // Get LOAD Positions
                $loadPos1 = new \Classes\Value\FloatValue($memberOverlap[0]);
                $loadPos2 = new \Classes\Value\FloatValue($memberOverlap[1]);
                $load->setProperty('position1', $loadPos1);
                $load->setProperty('position2', $loadPos2);
                
                // Get LOAD Values
                $value1 = self::$value1->get();
                $value2 = self::$value2->get();
                $loadValue1 =  new \Classes\Value\FloatValue($value1+($value2-$value1)*$loadOverlap[0]/$loadLineLength);
                $loadValue2 =  new \Classes\Value\FloatValue($value1+($value2-$value1)*$loadOverlap[1]/$loadLineLength);
                
                $isLoadAndMemberSameDirection = \Classes\Utils\Math\Lines::areTwoVectorHaveSameDirection($loadLine, $memberLine);
                if ($isLoadAndMemberSameDirection) {
                    $load->setProperty('value1', $loadValue1);
                    $load->setProperty('value2', $loadValue2);
                } else {
                    $load->setProperty('value1', $loadValue2);
                    $load->setProperty('value2', $loadValue1);
                }
                
                // Add connection
                self::$loadTable->setConnection($load->getUin(), $memberUin,
                        new \Classes\Factory\Connection\LoadConnection\GlobalCoordinateSystem);
                
                // Add new load
                self::setLoadProperties($load);
                
                $isBeginPartExist = !\Classes\Utils\Math\Constant::isNumbersEqual($loadOverlap[0], 0);
                $isEndPartExist = !\Classes\Utils\Math\Constant::isNumbersEqual($loadOverlap[1], $loadLineLength);
                
                // Get Uin of member's node, which is more far from sweep line
                switch ($memberEndNumber) {
                    case 0: $memberEdgeUin = $memberNode2->getUin(); break;
                    case 1: $memberEdgeUin = $memberNode1->getUin(); break;
                }
                
                // Check remainig parts of $loadLine
                // Begin part
                if ($isBeginPartExist) {
                    
                    // Find end point of begin part
                    $endPoint = \Classes\Utils\Math\Lines::getPointOnLineWithOffset($loadLine, $loadOverlap[0]);
                    
                    // Make new object
                    $beginObject = clone $commonMemberLoad;
                    
                    //Set Properties
                    $beginObject->setProperty('x2', new \Classes\Value\FloatValue($endPoint->x));
                    $beginObject->setProperty('y2', new \Classes\Value\FloatValue($endPoint->y));
                    $beginObject->setProperty('z2', new \Classes\Value\FloatValue($endPoint->z));
                    $beginObject->setProperty('value2', $loadValue1);
                    
                    self::$commonMemberLoadArray[$beginObject->getUin()] = $beginObject;
                    
                    //If begin part is near sweep line
                    if ($commonMemberLoadEndNumber == 0) {
                        self::$notFoundObjects[] = $beginObject->getUin();
                    }
                    //If begin part is far from sweep line
                    if ($commonMemberLoadEndNumber == 1) {
                        // Set connection btw 1st node of CommonMemberLoad and beginObject
                        self::$commonMemberLoadTable->setConnection($beginObject->getUin(),  $loadNode1->getUin(), self::$emptyConnection);
                        
                        // Set connection between end of member anв beginObject
                        self::$commonMemberLoadTable->setConnection($beginObject->getUin(),  $memberEdgeUin, self::$emptyConnection);
                    }
                    
                }
                
                // End part
                if ($isEndPartExist) {
                   
                    // Find begin point of end part
                    $beginPoint = \Classes\Utils\Math\Lines::getPointOnLineWithOffset($loadLine, $loadOverlap[1]);
                    
                    // Make new object
                    $endObject = clone $commonMemberLoad;
                    
                    //Set Properties
                    $endObject->setProperty('x1', new \Classes\Value\FloatValue($beginPoint->x));
                    $endObject->setProperty('y1', new \Classes\Value\FloatValue($beginPoint->y));
                    $endObject->setProperty('z1', new \Classes\Value\FloatValue($beginPoint->z));
                    $endObject->setProperty('value1', $loadValue2);
                    
                    self::$commonMemberLoadArray[$endObject->getUin()] = $endObject;
                    
                    //If begin part is near sweep line
                    if ($commonMemberLoadEndNumber == 0) {
                        // Set connection between end of member anв beginObject
                        self::$commonMemberLoadTable->setConnection($endObject->getUin(),  $memberEdgeUin, self::$emptyConnection);
                        
                        // Set connection btw 2nd node of CommonMemberLoad and beginObject
                        self::$commonMemberLoadTable->setConnection($endObject->getUin(),  $loadNode2->getUin(), self::$emptyConnection);
                    }
                    //If begin part is far from sweep line
                    if ($commonMemberLoadEndNumber == 1) {
                        self::$notFoundObjects[] = $endObject->getUin();
                    }
                }
                
                // Delete Common Member Load
                self::$commonMemberLoadTable->removeConnection($commonMemberLoad->getUin(), $loadNode1->getUin());
                self::$commonMemberLoadTable->removeConnection($commonMemberLoad->getUin(), $loadNode2->getUin());
                unset(self::$commonMemberLoadArray[$commonMemberLoad->getUin()]);
                
            }
         }
         
         return $isFound;
    }
    
    /*
     * Add concentrated CommonMemberLoad
     * 
     * @param \Classes\Instance\Node\Node $node Node
     * @param \Classes\Instance\Load\Member\CommonMemberLoad $commonMemberLoad Common Member Load
     * @param mixed[] $actualMemberUins Array of actual members
     * 
     * @return bool Successfully founded or not
     */
    private static function addConcentratedCommonMemberLoad($node, $commonMemberLoad, $actualMemberUins) {
        
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
        } else {
            self::$notFoundObjects[] = $commonMemberLoad->getUin();
        }
        
        return $isFound;
    }
}
