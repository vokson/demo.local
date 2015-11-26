<?php

namespace Classes\Factory\Model\Addition;

/**
 * Class for addition Member Load to Model
 *
 * @author Noskov Alexey
 */
class MemberLoadAddition {
    private static $object; // Object to be applied
    private static $loads; // New load
    private static $loadCase; // Load Case
    private static $name; // Name
    private static $direction; // Direction
    private static $x1,$y1,$z1; // Coordinates
    private static $value1; // Values
    
    private static $nodes, $members; // Model's arrays
    private static $hashTable, $loadTable; // Model's table
    
    private static $isFound; // Boolean
    private static $notFoundObject; // Object or it's part, which can't be found


    /*
     * Add Object to Model
     * 
     * @param \Classes\Instance\Load\Member\CommonMemberLoad $object
     * @param \Classes\Instance\Load\Member\CommonMemberLoad[] $notFoundObject Array of NOT found objects
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
        self::$loads = array();
        self::$notFoundObject = array();
        
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
            self::$notFoundObject = array(self::$object);
        
            // TRY TO APPLY TO NODE
            self::addNodeConcentratedLoad ();
            
            // TRY TO APPLY TO MEMBER
            if (!self::$isFound) {
                self::addMemberConcentratedLoad ();
            }
        }
        
        if ($type == "D") {
            self::addmemberDistributedLoad ();
        }
            
        foreach (self::$loads as $load) {
            // Set Properties
            $load->setProperty('loadCase', self::$loadCase);
            $load->setProperty('name', self::$name);
            $load->setProperty('direction', self::$direction);

            // Add to model
            \Classes\Factory\Model\Model::addInstance($load);
        }
        
        // If $object is NOT applied
        if (!self::$isFound) {
            // Return not found objects
            $notFoundObjects = self::$notFoundObject;
            return FALSE;
        }
        
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
                $load = new \Classes\Instance\Load\Node\NodeLoad;
                
                // Set Properties
                $load->setProperty('value', self::$value1);
                
                // Add connection
                self::$loadTable->setConnection($load->getUin(), $node->getUin(),
                        new \Classes\Factory\Connection\LoadConnection\GlobalCoordinateSystem);
                
                // Add new load to array
                self::$loads[] = $load;
                        
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
             
//             if ($node1Point->x == 2.0 && $node1Point->y == 2.6 && $node1Point->z == 15.0 &&
//                 $node2Point->x == 2.6 && $node2Point->y == 2.0 && $node2Point->z == 15.0) {
//                 echo "HERE<br/>";
//             }
            
            // Compare coordinates
            if (\Classes\Utils\Math\Points::isPointOnLine($loadPoint, $line) == 3) {
                
                self::$isFound = TRUE;
                
                // Make new node load
                $load = new \Classes\Instance\Load\Member\ConcenratedMemberLoad;
                
                $position = new \Classes\Value\FloatValue(
                        \Classes\Utils\Math\Points::twoPointsDistance($node1Point, $loadPoint));
                
                // Set Properties
                $load->setProperty('value', self::$value1);
                $load->setProperty('position', $position);
                
                
                // Add connection
                self::$loadTable->setConnection($load->getUin(), $member->getUin(),
                        new \Classes\Factory\Connection\LoadConnection\GlobalCoordinateSystem);
                
                // Add new load to array
                self::$loads[] = $load;
                
            } else {
                // If $object is NOT found, $object itself must be returned
                self::$notFoundObject = array(self::$object);
            }
            
            $i++;
        }
        
    }
    
     /*
     * Add Distributed Load to Model
     */
    private static function addMemberDistributedLoad() {
        self::addMemberDistributedLoadByMembers(array_values(self::$members), self::$object);
        
        self::$isFound = TRUE;
        
        if (count(self::$notFoundObject) > 0) {
            self::$isFound = FALSE;
        }
    }
    
    /*
     * Add Distributed Load to Model. Add NOT found parts into notFoundObject array
     * 
     * @param \Classes\Instance\Member\Member $members Array of members
     * @param \Classes\Instance\Load\Member\CommonMemberLoad $object
     */
    private static function addMemberDistributedLoadByMembers($members, $object) {
        
        $x1 = $object->getProperty('x1')->get();
        $y1 = $object->getProperty('y1')->get();
        $z1 = $object->getProperty('z1')->get();
        $value1 = $object->getProperty('value1')->get();
        
        $x2 = $object->getProperty('x2')->get();
        $y2 = $object->getProperty('y2')->get();
        $z2 = $object->getProperty('z2')->get();
        $value2 = $object->getProperty('value2')->get();
        
        // Prepare Load Point
        $loadPoint1 = new \Classes\Utils\AbstractInstance\Point($x1, $y1, $z1);
        $loadPoint2 = new \Classes\Utils\AbstractInstance\Point($x2, $y2, $z2);

        // Prepare Load Line
        $loadLine = new \Classes\Utils\AbstractInstance\Line($loadPoint1, $loadPoint2);
        $loadLineLength = $loadLine->length();
        
        // Try to find member with necessary coordinates
        $i=0; $isFound = FALSE;
        while ($i < count($members) && $isFound === FALSE) {
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
             $memberLine = new \Classes\Utils\AbstractInstance\Line($node1Point, $node2Point);
        
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
                $loadValue1 =  new \Classes\Value\FloatValue($value1+($value2-$value1)*$loadOverlap[0]/$loadLineLength);
                $loadValue2 =  new \Classes\Value\FloatValue($value1+($value2-$value1)*$loadOverlap[1]/$loadLineLength);
                
                if (\Classes\Utils\Math\Lines::areTwoVectorHaveSameDirection($loadLine, $memberLine)) {
                    $load->setProperty('value1', $loadValue1);
                    $load->setProperty('value2', $loadValue2);
                } else {
                    $load->setProperty('value1', $loadValue2);
                    $load->setProperty('value2', $loadValue1);
                }
                
                // Add connection
                self::$loadTable->setConnection($load->getUin(), $member->getUin(),
                        new \Classes\Factory\Connection\LoadConnection\GlobalCoordinateSystem);
                
                // Add new load to array
                self::$loads[] = $load;
                
                // Check rmainig parts of $loadLine
                // Begin part
                if (!\Classes\Utils\Math\Constant::isNumbersEqual($loadOverlap[0], 0)) {
                    
                    // Find end point of begin part
                    $endPoint = \Classes\Utils\Math\Lines::getPointOnLineWithOffset($loadLine, $loadOverlap[0]);
                    
                    // Make new object
                    $beginObject = clone $object;
                    
                    //Set Properties
                    $beginObject->setProperty('x2', new \Classes\Value\FloatValue($endPoint->x));
                    $beginObject->setProperty('y2', new \Classes\Value\FloatValue($endPoint->y));
                    $beginObject->setProperty('z2', new \Classes\Value\FloatValue($endPoint->z));
                    $beginObject->setProperty('value2', $loadValue1);
                    
                    self::addMemberDistributedLoadByMembers(array_slice($members, $i+1), $beginObject);
                    
                }
                
                // End part
                if (!\Classes\Utils\Math\Constant::isNumbersEqual($loadOverlap[1], $loadLineLength)) {
                   
                    // Find begin point of end part
                    $beginPoint = \Classes\Utils\Math\Lines::getPointOnLineWithOffset($loadLine, $loadOverlap[1]);
                    
                    // Make new object
                    $endObject = clone $object;
                    
                    //Set Properties
                    $endObject->setProperty('x1', new \Classes\Value\FloatValue($beginPoint->x));
                    $endObject->setProperty('y1', new \Classes\Value\FloatValue($beginPoint->y));
                    $endObject->setProperty('z1', new \Classes\Value\FloatValue($beginPoint->z));
                    $endObject->setProperty('value1', $loadValue2);
                    
                    self::addMemberDistributedLoadByMembers(array_slice($members, $i+1), $endObject);
                }
                
            }
            
            $i++;
        }
        
        // If $object has NOT been found
        if (!$isFound) {
            self::$notFoundObject[] = $object;
        }
        
    }
}
